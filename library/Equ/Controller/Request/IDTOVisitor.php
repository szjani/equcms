<?php
namespace Equ\Controller\Request;

interface IDTOVisitor {

  public function visitRequest(\Zend_Controller_Request_Abstract $request);

}