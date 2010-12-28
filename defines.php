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

//define('DOCTRINE_PATH', '/development/Frameworks/doctrine2-orm/lib/');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
  realpath(APPLICATION_PATH . '/../library'),
  '/development/Frameworks/ZF_1.11_svn/library',
  get_include_path()
)));
