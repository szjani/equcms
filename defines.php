<?php
error_reporting(E_ALL|E_STRICT|E_NOTICE);
ini_set('display_errors', true);

date_default_timezone_set('Europe/Budapest');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', __DIR__ . '/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
  '/development/Frameworks/ZF_1.11_svn/library',
  '/development/Frameworks/zf1-classmap/library',
  __DIR__ . '/library/ZFDebug/library',
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
      'Equ'      => __DIR__ . '/library/Equ/lib/Equ',
      'Symfony'  => __DIR__ . '/library/Symfony',
      'Doctrine' => '/home/szjani/development/php/libs/Doctrine-2.1/lib/Doctrine',
      'Gedmo'    => '/home/szjani/development/php/libs/l3pp4rd/DoctrineExtensions2.1/lib/Gedmo',
      'DoctrineExtensions' => '/home/szjani/development/php/libs/beberlei/DoctrineExtensions/lib/DoctrineExtensions',
    ),
    'prefixes' => array(
      'ZFDebug' => __DIR__ . '/library/ZFDebug/library/ZFDebug'
    ),
    'fallback_autoloader' => true,
  ),
));