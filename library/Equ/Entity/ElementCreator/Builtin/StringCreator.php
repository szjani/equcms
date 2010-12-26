<?php
namespace Equ\Entity\ElementCreator\Builtin;

class StringCreator extends \Equ\Entity\ElementCreator\StringCreator {

  protected function buildElement($fieldName) {
    $element = new \Zend_Form_Element_Text($fieldName);
    $element->setLabel($fieldName);
    return $element;
  }

}