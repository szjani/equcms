<?php
use Equ\Crud\Controller;

class RoleResource_AdminController extends Controller {

  protected function getService() {
    return $this->_helper->serviceContainer('roleresource');
  }

}