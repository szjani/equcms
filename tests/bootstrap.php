<?php
date_default_timezone_set('Europe/Budapest');

define('REBUILD_DATABASE', false);
define('APPLICATION_ENV', 'testing');

require_once dirname(__FILE__) . '/../defines.php';
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Factory');

$bootstrapper = new Factory_PHPUnit_TestBootstrapper();
$bootstrapper->bootstrapApplication();
echo $bootstrapper->getEnvInfos();

if (REBUILD_DATABASE) {
  $bootstrapper->rebuildDatabase();
}