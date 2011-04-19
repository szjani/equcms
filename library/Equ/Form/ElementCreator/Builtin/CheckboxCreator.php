<?php
namespace Equ\Form\ElementCreator\Builtin;

class CheckboxCreator extends \Equ\Form\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Form_Element_Checkbox($fieldName);
  }
  
}