<?php
namespace Equ\Controller\Action\Helper;

class ServiceContainer extends \Zend_Controller_Action_Helper_Abstract {

  /**
   * @var sfServiceContainer
   */
  protected $_container;

  public function direct($name) {
    return $this->getContainer()->get($name);
//    if ($this->getContainer()->has($name)) {
//    } else if ($this->getContainer()->hasParameter($name)) {
//      return $this->getContainer()->getParameter($name);
//    }
//    return null;
  }

  public function getContainer() {
    if ($this->_container === null) {
      $this->_container = $this->getActionController()->getInvokeArg('bootstrap')->getContainer();
    }
    return $this->_container;
  }

}