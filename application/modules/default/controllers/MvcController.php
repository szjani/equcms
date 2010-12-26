<?php
use Equ\Crud\Controller;

class MvcController extends Controller {

  protected function getService() {
    return $this->_helper->serviceContainer('mvc');
//    return $this->getInvokeArg('');
//    return new Default_Service_Mvc();
  }

}