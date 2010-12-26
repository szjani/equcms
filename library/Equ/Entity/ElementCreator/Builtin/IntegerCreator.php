<?php
namespace Equ\Entity\ElementCreator\Builtin;

class IntegerCreator extends \Equ\Entity\ElementCreator\IntegerCreator {

  protected function buildElement($fieldName) {
    $element = new \Zend_Form_Element_Text($fieldName);
    $element->setLabel($fieldName);
    return $element;
  }

}