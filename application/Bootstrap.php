<?php
class Bootstrap extends Equ\Application\Bootstrap\Bootstrap {

//  protected function _initAutoload() {
//    $autoloader = new Zend_Application_Module_Autoloader(array(
//      'namespace' => 'Application',
//      'basePath'  => dirname(__FILE__),
//    ));
//    $autoloader->addResourceType('lib', 'library', 'Library');
//    return $autoloader;
//  }

  protected function _initCleanQuery() {
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new \Equ\Controller\Plugin\CleanQuery());
  }

  protected function _initAdminroute() {
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new \Equ\Controller\Plugin\AdminRoute(), 30);
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
    $frontController->registerPlugin(new \Equ\Controller\Plugin\Language(), 40);
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
  }

  protected function _initAcl() {
    $this->bootstrap('doctrine');
    /* @var $cache Zend_Cache_Core */
    $cache = $this->getContainer()->get('cache.system');
    $acl = null;
    if ($acl = ($cache->load('acl'))) {
      $this->getContainer()->set('acl', $acl);
      $acl->setEntityManager($this->getContainer()->get('doctrine.entitymanager'));
    } else {
      $acl = $this->getContainer()->get('acl');
      $cache->save($acl, 'acl');
    }
//    var_dump($acl->has('mvc:admin.user-group.create'));
//    var_dump($acl->isAllowed('szjani@szjani.hu', 'mvc:admin.user-group.create'));
  }

  protected function _initMvcPermission() {
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new \Equ\Controller\Plugin\MvcPermission());
  }

  protected function _initDojo() {
    $this->bootstrap('view');
    /* @var $view Zend_View */
    $view = $this->getResource('view');
    Zend_Dojo::enableView($view);
  }

  protected function _initNavigation() {
    $user = $this->getContainer()->get('doctrine.entitymanager')->getRepository('entities\User')->findOneBy(array('email' => 'szjani@szjani.hu'));
    Zend_Auth::getInstance()->getStorage()->write($user);
    $this->bootstrap('frontController');
    $this->bootstrap('view');

    $frontController = $this->getResource('frontController');
    /* @var $frontController \Zend_Controller_Front */
    $frontController->registerPlugin(new \Equ\Controller\Plugin\Navigation());

//    $view->getHelper('navigation')->setContainer($this->getContainer()->get('navigation'));
//    $view->getHelper('navigation')->setAcl($this->getContainer()->get('acl'));
//    $view->getHelper('navigation')->setRole('szjani@szjani.hu');
//    $view->getHelper('navigation')->setRole('szjani@gmail.com');
//    $acl = new Zend_Acl();
//    $acl
//      ->addRole('szjani@gmail.com')
//      ->addResource('mvc:')
//      ->addResource('mvc:admin', 'mvc:')
//      ->addResource('mvc:admin.mvc', 'mvc:admin')
//      ->addResource('mvc:admin.mvc.list', 'mvc:admin.mvc')
//      ->addResource('mvc:admin.mvc.create', 'mvc:admin.mvc')
//      ->addResource('mvc:admin.user-group', 'mvc:admin')
//      ->addResource('mvc:admin.user-group.create', 'mvc:admin.user-group')
//      ->addResource('mvc:admin.user-group.list', 'mvc:admin.user-group')
//      ->addResource('mvc:admin.user', 'mvc:admin')
//      ->addResource('mvc:admin.user.create', 'mvc:admin.user')
//      ->addResource('mvc:admin.user.list', 'mvc:admin.user')
//      ->addResource('mvc:admin.role-resource', 'mvc:admin')
//      ->addResource('mvc:admin.role-resource.create', 'mvc:admin.role-resource')
//      ->addResource('mvc:admin.role-resource.list', 'mvc:admin.role-resource')
//      ->addResource('mvc:admin.role-resource.update', 'mvc:admin.role-resource')
//      ->allow('szjani@gmail.com', 'mvc:')
//      ->deny('szjani@gmail.com', 'mvc:admin.user');
//    $view->getHelper('navigation')->setAcl($acl);
//    $view->getHelper('navigation')->setRole('szjani@gmail.com');
  }

}
