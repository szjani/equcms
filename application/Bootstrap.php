<?php
use
  Equ\Controller\Plugin,
  Equ\Controller\Action\Helper,
  entities\User,
  entities\Mvc;

class Bootstrap extends Equ\Application\Bootstrap\Bootstrap {

  protected function _initAuthenticatedUserHelper() {
    $this->bootstrap('doctrine');
    $userRepo = $this->getContainer()->get('doctrine.entitymanager')->getRepository(User::className());
    Zend_Controller_Action_HelperBroker::addHelper(new Helper\AuthenticatedUser($userRepo));
  }

  protected function _initCleanQuery() {
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new Plugin\CleanQuery());
  }

  protected function _initAdminroute() {
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new Plugin\AdminRoute(), 30);
  }

  protected function _initAdminLayout() {
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new library\Controller\Plugin\AdminLayout());
  }

  protected function _initLangPlugin() {
    Zend_Registry::set(Zend_Application_Resource_Locale::DEFAULT_REGISTRY_KEY, new Zend_Locale('hu_HU'));
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    /* @var $frontController \Zend_Controller_Front */
    $frontController->registerPlugin(new Plugin\Language(), 40);
    return $this->getContainer()->get('registry');
  }

  protected function _initDefaultLog() {
    libxml_use_internal_errors(true);
    /* @var $log Zend_Log */
    $log = $this->getContainer()->get('log');
    $log->registerErrorHandler();
    return $log;
  }
  
  protected function _initDoctrine() {
    $config = $this->getContainer()->get('doctrine.configuration');
    $driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . '/entities');
    $config->setMetadataDriverImpl($driverImpl);
    $config->setProxyDir(APPLICATION_PATH . '/entities/Proxy');
    $config->setProxyNamespace('entities\Proxy');

    $evm = $this->getContainer()->get('doctrine.eventmanager');
    $treeListener = new \Gedmo\Tree\TreeListener();
    $evm->addEventSubscriber($treeListener);
    
    $timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
    $evm->addEventSubscriber($timestampableListener);
  }

  protected function _initAcl() {
    $this->bootstrap('doctrine');
    $container  = $this->getContainer();
    /* @var $cache Zend_Cache_Core */
    $cache = $container->get('cache.system');
    $acl   = $cache->load('acl');
    if (false !== $acl) {
      $container->set('acl', $acl);
      $acl->setEntityManager($container->get('doctrine.entitymanager'));
    } else {
      $acl = $container->get('acl');
      $cache->save($acl, 'acl');
    }
  }

  protected function _initMvcPermission() {
    $this->bootstrap('frontController');
    $this->bootstrap('doctrine');
    $this->bootstrap('acl');
    
    $container = $this->getContainer();
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new Plugin\MvcPermission(
      $container->get('doctrine.entitymanager')->getRepository(User::className()),
      $container->get('acl')
    ));
  }

  protected function _initDojo() {
    $this->bootstrap('view');
    /* @var $view Zend_View */
    $view = $this->getResource('view');
    Zend_Dojo::enableView($view);
  }

  protected function _initNavigation() {
    $this->bootstrap('frontController');
    $this->bootstrap('view');
    $this->bootstrap('doctrine');
    $this->bootstrap('acl');

    $frontController = $this->getResource('frontController');
    
    $container  = $this->getContainer();
    $frontController->registerPlugin(new Plugin\Navigation(
      $container,
      $container->get('doctrine.entitymanager')->getRepository(Mvc::className()),
      $container->get('cache.system'),
      $container->get('acl'),
      $this->getResource('view')
    ));
  }

}
