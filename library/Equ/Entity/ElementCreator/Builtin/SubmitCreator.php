<?php
namespace Equ\Entity\ElementCreator\Builtin;

class SubmitCreator extends \Equ\Entity\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Form_Element_Submit($fieldName);
  }

}