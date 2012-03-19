<?php
use
  Equ\Controller\Plugin,
  Equ\Controller\Action\Helper,
  Zend_Controller_Action_HelperBroker as HelperBroker;

class Bootstrap extends Equ\Application\Bootstrap\Bootstrap {

  protected function getMasterConfigFiles() {
    return array(
      APPLICATION_PATH . '/configs/production.xml',
      APPLICATION_PATH . '/configs/production_doctrine.xml',
      APPLICATION_PATH . '/configs/production_plugins.xml',
      APPLICATION_PATH . '/configs/development.xml',
    );
  }
  
  protected function _initGedmo() {
    $container = $this->getContainer();
    Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
      $container->get('doctrine.mappingdriver'), // our metadata driver chain, to hook into
      $container->get('annotation.reader') // our cached annotation reader
    );
  }
  
  protected function _initCache() {
    $container = $this->getContainer();
    $container->get('auto.page.cache')->start();
    
    Zend_Translate::setCache($container->get('cache.system'));
    Zend_Locale::setCache($container->get('cache.system'));
    Zend_Currency::setCache($container->get('cache.system'));
  }
  
  protected function _initHelpers() {
    $container = $this->getContainer();
    
    HelperBroker::addHelper(new Helper\ServiceInjector($container));
    HelperBroker::addHelper($container->get('form.builder'));
    HelperBroker::addHelper($container->get('redirect.here.after.post.helper'));
    HelperBroker::addHelper($container->get('authenticated.user.helper'));
    HelperBroker::addHelper($container->get('mvc.permission.helper'));
    HelperBroker::addHelper($container->get('lookup.helper'));
    HelperBroker::addHelper($container->get('available.languages.helper'));
    HelperBroker::addHelper($container->get('auto.title.helper'));
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
      ->registerPlugin($container->get('auto.page.cache'))
      ->registerPlugin($container->get('navigation.builder.plugin'))
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
  
  protected function _initNavigationHelper() {
    $this->bootstrap('view');
    $container  = $this->getContainer();
    
    $navigationHelper = $this->getResource('view')->getHelper('navigation');
    $navigationHelper
      ->setContainer($container->get('navigation'))
      ->setAcl($container->get('acl'))
      ->setRole($container->get('user.repository')->getAuthenticatedUser());
  }
  
}
