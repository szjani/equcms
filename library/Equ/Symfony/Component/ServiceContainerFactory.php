<?php
namespace Equ\Symfony\Component;
use Symfony\Component\DependencyInjection;

class ServiceContainerFactory {

  protected static $_container;

  public static function getContainer(array $options) {
    self::$_container = new DependencyInjection\ContainerBuilder();
    foreach ($options['configFiles'] as $file) {
      self::_loadConfigFile($file);
    }

    return self::$_container;
  }

  protected static function _loadConfigFile($file) {
    $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $loader = null;
    switch ($suffix) {
      case 'xml':
//        $loader = new sfServiceContainerLoaderFileXml(self::$_container);
        $loader = new DependencyInjection\Loader\XmlFileLoader(self::$_container);
        break;

      case 'yml':
        $loader = new DependencyInjection\Loader\YamlFileLoader(self::$_container);
        break;

//      case 'ini':
//        $loader = new sfServiceContainerLoaderFileIni(self::$_container);
//        break;

      default:
        throw new \Equ\Exception("Invalid configuration file provided; unknown config type '$suffix'");
    }
    $loader->load($file);
  }

}