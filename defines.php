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

define('PROJECT_CACHE_PREFIX', 'equcms_');

$zfDir      = '/development/Frameworks/ZF_1.11_svn/library';
$zfDebugDir = __DIR__ . '/library/ZFDebug/library';

$sources = array(
  'Zend'     => array($zfDir, '_'),
  'ZFDebug'  => array($zfDebugDir, '_'),
  'Doctrine' => '/development/Frameworks/Doctrine-2.2/lib',
  'Gedmo'    => __DIR__ . '/library/Gedmo/lib',
  'library'  => APPLICATION_PATH,
  'entities' => APPLICATION_PATH,
  'modules'  => APPLICATION_PATH,
  'Equ'      => __DIR__ . '/library/Equ/lib',
  'Symfony'  => __DIR__ . '/library',
);

set_include_path(implode(PATH_SEPARATOR, array(
  $zfDir, $zfDebugDir
)));

require_once __DIR__ . '/library/Symfony/Component/ClassLoader/UniversalClassLoader.php';
require_once __DIR__ . '/library/Symfony/Component/ClassLoader/ApcUniversalClassLoader.php';

use Symfony\Component\ClassLoader\ApcUniversalClassLoader;
$loader = new ApcUniversalClassLoader(PROJECT_CACHE_PREFIX);
$loader->register();

foreach ($sources as $namespace => $source) {
  $dir = $source;
  $separator = '\\';
  if (is_array($source)) {
    $dir = array_shift($source);
    if (!empty($source)) {
      $separator = array_shift($source);
      $loader->registerPrefix($namespace, $dir);
    }
  } else {
    $loader->registerNamespace($namespace, $dir);
  }
}
Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
  'Doctrine\ORM\Mapping',
  $sources['Doctrine']
);
