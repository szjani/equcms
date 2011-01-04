<?php
namespace Equ\Controller\Action\Helper;

class ServiceContainer extends \Zend_Controller_Action_Helper_Abstract {

  protected $_container;

  public function direct($name) {
    return $this->getContainer()->get($name);
  }

  public function getContainer() {
    if ($this->_container === null) {
      $this->_container = $this->getActionController()->getInvokeArg('bootstrap')->getContainer();
    }
    return $this->_container;
  }

}