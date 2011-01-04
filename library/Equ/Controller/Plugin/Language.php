<?php
namespace Equ\Controller\Plugin;

class Language extends \Zend_Controller_Plugin_Abstract {

  /**
   * (non-PHPdoc)
   * @see \Zend_Controller_Plugin_Abstract::routeStartup()
   */
  public function routeStartup(\Zend_Controller_Request_Abstract $request) {
    $locale = \Zend_Registry::get('Zend_Locale');
    $front  = \Zend_Controller_Front::getInstance();
    $router = $front->getRouter();

    // lang route
    $routeLang = new \Zend_Controller_Router_Route(
      ':lang',
      array(
        'lang' => $locale->getLanguage(),
      ),
      array('lang' => '[a-z]{2}')
    );

    // default routes
    $router->addDefaultRoutes();

    // create chain routes
    foreach ($router->getRoutes() as $name => $route) {
      $chain = new \Zend_Controller_Router_Route_Chain();
      $router->addRoute(
        $name . 'lang',
        $chain->chain($routeLang)->chain($route)
      );
    }

    // add simple lang route
    $router->addRoute('lang', $routeLang);
  }

  /**
   * (non-PHPdoc)
   * @see \Zend_Controller_Plugin_Abstract::routeShutdown()
   */
  public function routeShutdown(\Zend_Controller_Request_Abstract $request) {
    $origLang  = $lang = $request->getParam('lang');
    $translate = \Zend_Registry::get('Zend_Translate');

    // Change language if available
    if ($translate->isAvailable($lang)) {
      $translate->setLocale($lang);
      \Zend_Registry::get('Zend_Locale')->setLocale($lang);
    } else {
      // Otherwise get default language
      $locale = $translate->getLocale();
      $lang = ($locale instanceof \Zend_Locale) ? $locale->getLanguage() : $locale;

      // there is az invalid lang param in request
      if (isset($origLang)) {
        throw new \Exception("Invalid language '$origLang'");
      }
    }
    
    $router = \Zend_Controller_Front::getInstance()->getRouter();
    /* @var $router Zend_Controller_Router_Rewrite */
    if (false !== \strpos($router->getCurrentRouteName(), 'lang')) {
      $router->setGlobalParam('lang', $lang);
    }
    $request->setParam('lang', $lang);
  }

}