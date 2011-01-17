<?php
define('APPLICATION_ENV', 'development');

require_once dirname(__FILE__) . '/../defines.php';
require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);