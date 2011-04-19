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
  realpath(APPLICATION_PATH),
  realpath(APPLICATION_PATH . '/../library'),
  '/home/szjani/development/php/libs/ZF/1.11/library',
  '/home/szjani/development/php/libs/doctrine2-orm/lib',
  '/home/szjani/development/php/libs/beberlei/DoctrineExtensions/lib',
  '/home/szjani/development/php/libs/l3pp4rd/DoctrineExtensions/lib',
//  '/development/Frameworks/ZF_1.11_svn/library',
//  '/development/Frameworks/Doctrine-2.0',
//  '/development/Frameworks/beberlei/DoctrineExtensions/lib',
//  '/development/Frameworks/doctrine2-extensions/lib',
  get_include_path()
)));
