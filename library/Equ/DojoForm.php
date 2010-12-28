<?php
namespace Equ;

class DojoForm extends \Zend_Dojo_Form implements Form\Visitable {

  public function accept(FormVisitor $visitor) {
    $visitor->visitForm($this);
  }
}