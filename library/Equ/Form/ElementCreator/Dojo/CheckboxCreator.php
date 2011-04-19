<?php
namespace Equ\Form\ElementCreator\Dojo;

class CheckboxCreator extends \Equ\Form\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Dojo_Form_Element_CheckBox($fieldName);
  }
  
}