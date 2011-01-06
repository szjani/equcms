<?php
use Equ\Crud\Controller;

class UserGroup_AdminController extends Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  protected function getService() {
    return $this->_helper->serviceContainer('usergroup');
  }

}