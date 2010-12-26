<?php

try {
  if ($GLOBALS['argc'] !== 2) {
    throw new Exception('Use php genappini.php environment');
  }

  define('APPLICATION_ENV', $GLOBALS['argv'][1]);
  require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';
  
  require_once 'Zend/Loader/Autoloader.php';

  $autoloader = Zend_Loader_Autoloader::getInstance();
  $autoloader->registerNamespace('Factory');

  $cManager = new Factory_Config_Manager(APPLICATION_PATH . '/configs', APPLICATION_ENV);
  $cManager->needCache(false);
  $cManager->getMergedConfig();
} catch (Exception $e) {
  echo $e->getMessage()."\n";
}
