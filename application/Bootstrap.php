<?php
use
  Equ\Controller\Plugin,
  Equ\Controller\Action\Helper,
  entities\User,
  entities\Mvc,
  modules\user\plugins\AclInitializer,
  modules\user\models\Anonymous,
  Zend_Controller_Action_HelperBroker as HelperBroker;
use
  Symfony\Component\DependencyInjection,
  Equ\Symfony\Component\ServiceContainerFactory,
  Equ\Controller\Action\Helper\ServiceContainer;

class Bootstrap {

  protected $_container;
  
  protected $environment;
  
  public function __construct($environment) {
    $this->environment = $environment;
  }
  
  public function getEnvironment() {
    return $this->environment;
  }
  
  public function init() {
    $cache = $this->getContainer()->get('cache.system');
    Zend_Translate::setCache($cache);
    Zend_Locale::setCache($cache);
    Zend_Currency::setCache($cache);
    
    $this->_initHelpers();
    $this->_initAnonymousUser();
    $this->_initDefaultLocalePlugin();
    $this->_initDefaultLog();
    $this->_initNavigation();
    $this->_initPlugins();
    $this->getContainer()->get('layout');
    return $this;
  }
  
  public function run() {
    $this->getContainer()->get('front.controller')->dispatch();
  }
  
  protected function getMasterConfigFiles() {
    return array(
      APPLICATION_PATH . '/configs/production.xml',
      APPLICATION_PATH . '/configs/production_doctrine.xml',
      APPLICATION_PATH . '/configs/production_plugins.xml',
      APPLICATION_PATH . '/configs/development.xml',
    );
  }
  
  protected function _initHelpers() {
    $container = $this->getContainer();
    $container->get('service.container.helper')->setContainer($container);
    
    HelperBroker::addPath(APPLICATION_PATH . '/../library/Equ/lib/Equ/Controller/Action/Helper', 'Equ\Controller\Action\Helper\\');

    HelperBroker::addHelper(new Helper\ServiceInjector($container));
    HelperBroker::addHelper($container->get('form.builder'));
    HelperBroker::addHelper($container->get('redirect.here.after.post.helper'));
    HelperBroker::addHelper($container->get('authenticated.user.helper'));
    HelperBroker::addHelper($container->get('mvc.permission.helper'));
    HelperBroker::addHelper($container->get('lookup.helper'));
    HelperBroker::addHelper($container->get('view.renderer.helper'));
    HelperBroker::addHelper($container->get('service.container.helper'));
    
  }
  
  protected function _initPlugins() {
    $container = $this->getContainer();
    $frontController = $container->get('front.controller');
    
    $frontController
      ->registerPlugin($container->get('clean.query.plugin'))
      ->registerPlugin($container->get('admin.route.plugin'), 30)
      ->registerPlugin($container->get('admin.layout.plugin'))
      ->registerPlugin($container->get('language.plugin'), 40)
//      ->registerPlugin($container->get('zfdebug.plugin'))
      ->registerPlugin($container->get('anonymous.acl.init.plugin'));
  }

  protected function _initDefaultLocalePlugin() {
    $this->getContainer()->get('registry');
  }

  protected function _initDefaultLog() {
    libxml_use_internal_errors(true);
    /* @var $log Zend_Log */
    return $this->getContainer()->get('log');
  }
  
  protected function _initNavigation() {
    $container  = $this->getContainer();
    $frontController = $container->get('front.controller');
    $frontController->registerPlugin(new Plugin\Navigation(
      $container->get('navigation'),
      $container->get('doctrine.entitymanager')->getRepository(Mvc::className()),
      $container->get('cache.system'),
      $container->get('acl'),
      $container->get('view')
    ));
  }
  
  protected function _initAnonymousUser() {
    $auth = Zend_Auth::getInstance();
    if (!$auth->hasIdentity()) {
      $auth->getStorage()->write(new Anonymous());
    }
  }
  
  private function getCache() {
    $cache = \Zend_Cache::factory(
      'File',
      'File',
      array(// Frontend Default Options
        'master_files' => $this->getMasterConfigFiles(),
        'automatic_serialization' => true
      ),
      array(// Backend Default Options
        'cache_dir' => APPLICATION_PATH . '/../data/cache'
      )
    );
    return $cache;
  }
  
  public function getContainer() {
    if (null === $this->_container) {
      $container = null;
      $name = 'Container'.$this->getEnvironment().'ServiceContainer';
      $file = APPLICATION_PATH.'/../data/cache/'.$name.'.php';
      
      $cache = $this->getCache();
      $diContainerLoaded = $cache->load('DIContainerLoaded');
      if (!$diContainerLoaded) {
        $cache->save('loaded', 'DIContainerLoaded');
      }
      
      if ($diContainerLoaded && file_exists($file)) {
        require_once $file;
        $container = new $name();
      } else {
        $options = array(
          'type' => 'symfony',
          'configFiles' => array(
            APPLICATION_PATH .  "/configs/{$this->getEnvironment()}.xml"
          )
        );
        $container = ServiceContainerFactory::getContainer($options);
        $dumper = new DependencyInjection\Dumper\PhpDumper($container);
        file_put_contents($file, $dumper->dump(array('class' => $name)));
      }
      $this->_container = $container;
    }
    return $this->_container;
  }

}
