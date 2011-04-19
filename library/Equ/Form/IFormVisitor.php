<?php
namespace Equ\Form;

interface IFormVisitor {

  public function visitForm(\Zend_Form $form);

}