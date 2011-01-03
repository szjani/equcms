<?php
class Bootstrap extends Equ\Application\Bootstrap\Bootstrap {

  protected function _initAutoload() {
    $autoloader = new Zend_Application_Module_Autoloader(array(
      'namespace' => 'Application',
      'basePath'  => dirname(__FILE__),
    ));
    $autoloader
      ->addResourceType('lib', 'library', 'Library');
//      ->addResourceType('entities', 'entities', 'Entity')
//      ->addResourceType('proxies', 'proxies', 'Proxy');
    return $autoloader;
  }

//  protected function _initServiceContainerHelper() {
//    $this->bootstrap('frontController');
//    $frontController = $this->getResource('frontController');
//    /* @var $frontController Zend_Controller_Front */
//    $frontController->re
//    $frontController->registerPlugin(new \Equ\Controller\Action\Helper\ServiceContainer());
//  }

  protected function _initDefaultLog() {
    libxml_use_internal_errors(true);
    /* @var $log Zend_Log */
    $log = $this->getContainer()->get('log');
    new Factory_EventLogger($log);
    $log->registerErrorHandler();
    return $log;
  }
  
  protected function _initRegistry() {
    return $this->getContainer()->get('registry');
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

    /* @var $em Doctrine\ORM\EntityManager */
//    $em = $this->getContainer()->get('doctrine.entitymanager');
  }

  protected function _initNavigation() {
    $this->bootstrap('view');
//    $container = new Zend_Navigation();
    $container = $this->getContainer()->get('navigation');
    /* @var $em \Doctrine\ORM\EntityManager */
    $em = $this->getContainer()->get('doctrine.entitymanager');
    $leaves = $em->getRepository('\entities\Mvc')->getLeafs();
    /* @var $mvc \entities\Mvc */
    foreach ($leaves as $mvc) {
      $parent = $mvc->getParent();
      while ($parent !== null) {
//        var_dump((string)$mvc->getNavigationPage()->getResource());
        if (false !== strpos((string)$mvc->getNavigationPage()->getResource(), 'update')) {
          $mvc->getNavigationPage()->setVisible(false);
        }
        $parent->getNavigationPage()->addPage($mvc->getNavigationPage());
        $mvc = $parent;
        $parent = $mvc->getParent();
      }
      $container->addPage($mvc->getNavigationPage());
    }

    /* @var $view Zend_View */
    $view = $this->getResource('view');
    Zend_Dojo::enableView($view);
    $view->getHelper('navigation')->setContainer($container);
    $view->getHelper('navigation')->setAcl($this->getContainer()->get('acl'));
    $view->getHelper('navigation')->setRole('szjani@szjani.hu');
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
