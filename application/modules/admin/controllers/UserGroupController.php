<?php
use Equ\Crud\Controller;

class Admin_UserGroupController extends Controller {
  
	/**
   * @see Equ\Crud.Controller::getService()
   */
  protected function getService() {
    return $this->_helper->serviceContainer('usergroup');
  }

}