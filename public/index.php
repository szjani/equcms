<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';

Zend_Session::start();

require_once APPLICATION_PATH . '/Bootstrap.php';
$bootstrap = new Bootstrap(APPLICATION_ENV);
$bootstrap
  ->init()
  ->run();

Zend_Session::writeClose(true);