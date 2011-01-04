<?php
use Equ\Crud\Controller;

class Mvc_AdminController extends Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'resource');

  protected function getService() {
    return $this->_helper->serviceContainer('mvc');
  }

}