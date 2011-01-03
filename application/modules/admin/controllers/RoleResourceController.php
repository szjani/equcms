<?php
use Equ\Crud\Controller;

class Admin_RoleResourceController extends Controller {
  
	/**
   * @see Equ\Crud.Controller::getService()
   */
  protected function getService() {
    return $this->_helper->serviceContainer('roleresource');
  }

}