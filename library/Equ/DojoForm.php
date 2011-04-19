<?php
namespace Equ;

class DojoForm extends \Zend_Dojo_Form implements Form\IVisitable {

  public function accept(IFormVisitor $visitor) {
    $visitor->visitForm($this);
  }
}