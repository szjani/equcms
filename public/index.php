<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';

Zend_Session::setOptions(array('cookie_httponly' => true));

// Create application, bootstrap, and run
$application = new Equ\Application(
  APPLICATION_ENV,
  array('configFile' => APPLICATION_PATH . '/configs/application.ini')
);
$application->bootstrap();
$application->run();

Zend_Session::writeClose(true);