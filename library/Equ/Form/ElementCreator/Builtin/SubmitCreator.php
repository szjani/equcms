<?php
namespace Equ\Form\ElementCreator\Builtin;

class SubmitCreator extends \Equ\Form\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Form_Element_Submit($fieldName);
  }

}