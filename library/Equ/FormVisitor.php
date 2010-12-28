<?php
namespace Equ;

interface FormVisitor {

  public function visitForm(\Zend_Form $form);

}