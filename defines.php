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
  '/home/szjani/development/php/libs/ZF/1.11/library',
  '/development/Frameworks/zf1-classmap/library',
)));

require_once 'ZendX/Loader/AutoloaderFactory.php';
ZendX_Loader_AutoloaderFactory::factory(array(
  'ZendX_Loader_ClassMapAutoloader' => array(
      __DIR__ . '/library/.classmap.php',
      __DIR__ . '/application/.classmap.php',
  ),
  'ZendX_Loader_StandardAutoloader' => array(
    'namespaces' => array(
      'library'  => APPLICATION_PATH . '/library',
      'entities' => APPLICATION_PATH . '/entities',
      'modules'  => APPLICATION_PATH . '/modules',
      'services' => APPLICATION_PATH . '/services',
      'plugins'  => APPLICATION_PATH . '/plugins',
      'Equ'      => __DIR__ . '/library/Equ',
      'Symfony'  => __DIR__ . '/library/Symfony',
      'Doctrine' => '/home/szjani/development/php/libs/doctrine2-orm/lib/Doctrine',
      'Gedmo'    => '/home/szjani/development/php/libs/l3pp4rd/DoctrineExtensions/lib/Gedmo',
      'DoctrineExtensions' => '/home/szjani/development/php/libs/beberlei/DoctrineExtensions/lib/DoctrineExtensions',
    ),
    'fallback_autoloader' => true,
  ),
));