<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
  APPLICATION_ENV,
  APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$application->run();

Zend_Session::writeClose(true);