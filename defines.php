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
require_once 'Doctrine/Common/ClassLoader.php';
use Doctrine\Common\ClassLoader;

$autoloader = Zend_Loader_Autoloader::getInstance();

$autoloader
  ->pushAutoloader(array(new ClassLoader('DoctrineExtensions'), 'loadClass'), 'DoctrineExtensions')
  ->pushAutoloader(array(new ClassLoader('Doctrine'), 'loadClass'), 'Doctrine')
  ->pushAutoloader(array(new ClassLoader('Symfony'), 'loadClass'), 'Symfony')
  ->pushAutoloader(array(new ClassLoader('Gedmo'), 'loadClass'), 'Gedmo')
  ->pushAutoloader(array(new ClassLoader('Equ'), 'loadClass'), 'Equ')
  ->pushAutoloader(array(new ClassLoader('modules', APPLICATION_PATH), 'loadClass'), 'modules')
  ->pushAutoloader(array(new ClassLoader('entities', APPLICATION_PATH), 'loadClass'), 'entities')
  ->pushAutoloader(array(new ClassLoader('library', APPLICATION_PATH), 'loadClass'), 'library')
  ->pushAutoloader(array(new ClassLoader('services', APPLICATION_PATH), 'loadClass'), 'services')
  ->pushAutoloader(array(new ClassLoader('plugins', APPLICATION_PATH), 'loadClass'), 'plugins');