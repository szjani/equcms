<?php
namespace Equ\Application\Bootstrap;
use Symfony\Component\DependencyInjection;
use Equ\Symfony\Component\ServiceContainerFactory;
use Equ\Controller\Action\Helper\ServiceContainer;

class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap {

  public function getContainer() {
    $options = $this->getOption('bootstrap');

    if (null === $this->_container && $options['container']['type'] == 'symfony') {
//      spl_autoload_register(array('Doctrine_Core', 'autoload'));
//      require_once 'sfServiceContainer/sfServiceContainerAutoloader.php';
//      sfServiceContainerAutoloader::register();
      $container = null;
      $name = 'Container'.md5(APPLICATION_ENV).'ServiceContainer';
      $file = APPLICATION_PATH.'/../data/cache/'.$name.'.php';
      if (defined('APPLICATION_ENV') && APPLICATION_ENV !== 'development' && file_exists($file)) {
        require_once $file;
        $container = new $name();
      } else {
        $container = ServiceContainerFactory::getContainer($options['container']);
//        $dumper = new sfServiceContainerDumperPhp($container);
        $dumper = new DependencyInjection\Dumper\PhpDumper($container);
        file_put_contents($file, $dumper->dump(array('class' => $name)));
      }
      $this->_container = $container;
      \Zend_Controller_Action_HelperBroker::addHelper(new ServiceContainer());
    }
    return parent::getContainer();
  }

}