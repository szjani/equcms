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

$zfDir      = '/development/Frameworks/ZF_1.11_svn/library';
$zfDebugDir = __DIR__ . '/library/ZFDebug/library';

$sources = array(
  'Zend'     => array('/development/Frameworks/ZF_1.11_svn/library', '_'),
  'ZFDebug'  => array(__DIR__ . '/library/ZFDebug/library', '_'),
  'Doctrine' => '/development/Frameworks/Doctrine-2.2/lib',
  'Gedmo'    => '/development/Frameworks/l3pp4rd_2.2/lib',
  'library'  => APPLICATION_PATH,
  'entities' => APPLICATION_PATH,
  'modules'  => APPLICATION_PATH,
  'Equ'      => __DIR__ . '/library/Equ/lib',
  'Symfony'  => __DIR__ . '/library',
);

set_include_path(implode(PATH_SEPARATOR, array(
  $zfDir, $zfDebugDir
)));

require_once $sources['Doctrine'] . '/Doctrine/Common/ClassLoader.php';
use Doctrine\Common\ClassLoader;

foreach ($sources as $namespace => $source) {
  $dir = $source;
  $separator = '\\';
  if (is_array($source)) {
    $dir = array_shift($source);
    if (!empty($source)) {
      $separator = array_shift($source);
    }
  }
  $loader = new ClassLoader($namespace, $dir);
  $loader->setNamespaceSeparator($separator);
  $loader->register();
}
Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
  'Doctrine\ORM\Mapping',
  $sources['Doctrine']
);
