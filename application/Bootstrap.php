<?php
class Bootstrap extends Equ\Application\Bootstrap\Bootstrap {

  protected function _initAutoload() {
    $autoloader = new Zend_Application_Module_Autoloader(array(
      'namespace' => 'Application',
      'basePath'  => dirname(__FILE__),
    ));
    $autoloader
      ->addResourceType('lib', 'library', 'Library')
//      ->addResourceType('entities', 'entities', 'Entity')
      ->addResourceType('proxies', 'proxies', 'Proxy');
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
    $driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . '/Entity');
    $config->setMetadataDriverImpl($driverImpl);
    $config->setProxyDir(APPLICATION_PATH . '/Entity/Proxy');
    $config->setProxyNamespace('Entity\Proxy');

    $evm = $this->getContainer()->get('doctrine.eventmanager');
    $treeListener = new \Gedmo\Tree\TreeListener();
    $evm->addEventSubscriber($treeListener);

    /* @var $em Doctrine\ORM\EntityManager */
//    $em = $this->getContainer()->get('doctrine.entitymanager');
  }

  protected function _initNavigation() {
    $this->bootstrap('view');
    $container = new Zend_Navigation();
    /* @var $em \Doctrine\ORM\EntityManager */
    $em = $this->getContainer()->get('doctrine.entitymanager');
    $leaves = $em->getRepository('\Entity\Mvc')->getLeafs();
    /* @var $mvc \Entity\Mvc */
    foreach ($leaves as $mvc) {
      $parent = $mvc->getParent();
      while ($parent !== null) {
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
//    $view->getHelper('navigation')->setAcl($this->getContainer()->get('acl'));
//    $container->
  }

}
