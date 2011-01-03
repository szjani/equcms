<?php
namespace Equ\Entity\ElementCreator\Dojo;

class CheckboxCreator extends \Equ\Entity\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Dojo_Form_Element_CheckBox($fieldName);
  }
  
}