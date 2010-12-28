<?php
namespace Equ;

class AbstractService {

  /**
   *
   * @var \Zend_Log
   */
  private $log = null;

  /**
   * @return \Zend_Log
   */
  public function getLog() {
    if ($this->log === null) {
      $this->log = \Zend_Controller_Front::getInstance()->getParam('bootstrap')
        ->getContainer()->get('log');
    }
    return $this->log;
  }

  /**
   * @param \Zend_Log $log
   * @return AbstractService
   */
  public function setLog(\Zend_Log $log) {
    $this->log = $log;
    return $this;
  }

}