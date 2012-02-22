<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';
require_once APPLICATION_PATH . '/Bootstrap2.php';

$bootstrap = new Bootstrap2(APPLICATION_ENV);
$bootstrap
  ->init()
  ->run();

Zend_Session::writeClose(true);