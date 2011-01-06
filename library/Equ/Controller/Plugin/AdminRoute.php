<?php
namespace Equ\Controller\Plugin;

class AdminRoute extends \Zend_Controller_Plugin_Abstract {

  /**
   * (non-PHPdoc)
   * @see \Zend_Controller_Plugin_Abstract::routeStartup()
   */
  public function routeStartup(\Zend_Controller_Request_Abstract $request) {
    $front  = \Zend_Controller_Front::getInstance();
    $router = $front->getRouter();
    $adminRoute = new \Zend_Controller_Router_Route(
      ':controller/:module/:action/*',
      array(
        'module' => 'index',
        'controller' => 'index',
        'action' => 'index'
      ),
      array(
        'controller' => 'admin'
      )
    );
    $router->addRoute('admin', $adminRoute);
  }
}