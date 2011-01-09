<?php
namespace Equ\Controller\Plugin;

class CleanQuery extends \Zend_Controller_Plugin_Abstract {

  public function routeShutdown(\Zend_Controller_Request_Abstract $request) {
    if (!$request->isXmlHttpRequest() && preg_match('/\?/', $request->getRequestUri())) {
      $params = $request->getParams();
      $newParams = array();
      foreach ($params as $key => $param) {
        if (trim($param) !== '') {
          $newParams[$key] = $param;
        }
      }
      \Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')->gotoRouteAndExit($newParams);
    }
  }

}