<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';
require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Factory');

$bootstrapper = new Factory_Bootstrapper();
$bootstrapper->getConfigManager()->needCache(APPLICATION_ENV != 'development');

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    $bootstrapper->getConfigManager()->getMergedConfig()
);
//$start = microtime(true);
$application->bootstrap();
//var_dump(microtime(true) - $start);
$application->run();

Zend_Session::writeClose(true);