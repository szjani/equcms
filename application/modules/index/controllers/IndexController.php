<?php
class IndexController extends \Zend_Controller_Action {
  
  public function init() {
    parent::init();
    $this->_helper->autoTitle();
  }
  
  public function indexAction() {
  }
}
