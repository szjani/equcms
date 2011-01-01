<?php
use Equ\Crud\Controller;

class Admin_MvcController extends Controller {

  protected function getService() {
//    $test = new \modules\index\models\Test();
    return $this->_helper->serviceContainer('mvc');
//    return $this->getInvokeArg('');
//    return new Default_Service_Mvc();
  }

}