<?php
namespace Equ;

class Form extends \Zend_Form implements Form\Visitable {

  public function accept(FormVisitor $visitor) {
    $visitor->visitForm($this);
  }
}