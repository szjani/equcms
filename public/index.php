<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';

// Create application, bootstrap, and run
$application = new Equ\Application(
  APPLICATION_ENV,
  array('configFile' => APPLICATION_PATH . '/configs/application.ini')
);
$application->bootstrap();
$application->run();

if (Zend_Session::getIterator()->count() == 0) {
  Zend_Session::expireSessionCookie();
}
Zend_Session::writeClose(true);