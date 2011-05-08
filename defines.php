<?php
error_reporting(E_ALL|E_STRICT|E_NOTICE);
ini_set('display_errors', true);

date_default_timezone_set('Europe/Budapest');

defined('CURRENT_DATE')
    || define('CURRENT_DATE', date('Y-m-d'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
  realpath(APPLICATION_PATH . '/../library'),
  '/home/szjani/development/php/libs/ZF/1.11/library',
  '/home/szjani/development/php/libs/doctrine2-orm/lib',
  '/home/szjani/development/php/libs/beberlei/DoctrineExtensions/lib',
  '/home/szjani/development/php/libs/l3pp4rd/DoctrineExtensions/lib',
  get_include_path()
)));

require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();

require_once 'Doctrine/Common/ClassLoader.php';

$doctrineAutoloader = new \Doctrine\Common\ClassLoader('Doctrine');
$doctrineAutoloader->register();

$symfonyAutoloader = new \Doctrine\Common\ClassLoader('Symfony');
$symfonyAutoloader->register();

$gedmoAutoloader = new \Doctrine\Common\ClassLoader('Gedmo');
$gedmoAutoloader->register();

$extAutoloader = new \Doctrine\Common\ClassLoader('DoctrineExtensions');
$extAutoloader->register();

$equAutoloader = new \Doctrine\Common\ClassLoader('Equ');
$equAutoloader->register();

$appAutoloader = new \Doctrine\Common\ClassLoader('modules', APPLICATION_PATH);
$appAutoloader->register();
$appAutoloader = new \Doctrine\Common\ClassLoader('entities', APPLICATION_PATH);
$appAutoloader->register();
$appAutoloader = new \Doctrine\Common\ClassLoader('library', APPLICATION_PATH);
$appAutoloader->register();
$appAutoloader = new \Doctrine\Common\ClassLoader('services', APPLICATION_PATH);
$appAutoloader->register();
$appAutoloader = new \Doctrine\Common\ClassLoader('plugins', APPLICATION_PATH);
$appAutoloader->register();