<?php
use
  Equ\Controller\Plugin,
  Equ\Controller\Action\Helper,
  entities\User,
  entities\Mvc,
  modules\user\plugins\AclInitializer,
  modules\user\models\Anonymous,
  Zend_Controller_Action_HelperBroker as HelperBroker;

class Bootstrap extends Equ\Application\Bootstrap\Bootstrap {

  protected function getMasterConfigFiles() {
    return array(
      APPLICATION_PATH . '/configs/production.xml',
      APPLICATION_PATH . '/configs/production_doctrine.xml',
      APPLICATION_PATH . '/configs/development.xml',
    );
  }
  
  protected function _initHelpers() {
    $container = $this->getContainer();
    
    HelperBroker::addHelper(new Helper\ServiceInjector($container));
    HelperBroker::addHelper($container->get('form.builder'));
    HelperBroker::addHelper($container->get('redirect.here.after.post.helper'));
    HelperBroker::addHelper($container->get('authenticated.user.helper'));
    HelperBroker::addHelper($container->get('mvc.permission.helper'));
  }
  
  protected function _initPlugins() {
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $container = $this->getContainer();
    
    $frontController
      ->registerPlugin($container->get('clean.query.plugin'))
      ->registerPlugin($container->get('admin.route.plugin'), 30)
      ->registerPlugin($container->get('admin.layout.plugin'))
      ->registerPlugin($container->get('language.plugin'), 40)
      ->registerPlugin($container->get('anonymous.acl.init.plugin'));
  }

  protected function _initDefaultLocalePlugin() {
    Zend_Registry::set(Zend_Application_Resource_Locale::DEFAULT_REGISTRY_KEY, new Zend_Locale('hu_HU'));
    return $this->getContainer()->get('registry');
  }

  protected function _initDefaultLog() {
    libxml_use_internal_errors(true);
    /* @var $log Zend_Log */
    return $this->getContainer()->get('log');
  }
  
  protected function _initNavigation() {
    $this->bootstrap('frontController');
    $this->bootstrap('view');

    $container  = $this->getContainer();
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new Plugin\Navigation(
      $container->get('navigation'),
      $container->get('doctrine.entitymanager')->getRepository(Mvc::className()),
      $container->get('cache.system'),
      $container->get('acl'),
      $this->getResource('view')
    ));
  }
  
  protected function _initAnonymousUser() {
    $this->bootstrap('helpers');
    $auth = Zend_Auth::getInstance();
    if (!$auth->hasIdentity()) {
      $auth->getStorage()->write(new Anonymous());
    }
  }

}
