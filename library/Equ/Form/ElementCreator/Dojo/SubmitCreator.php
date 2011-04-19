<?php
namespace Equ\Form\ElementCreator\Dojo;

class SubmitCreator extends \Equ\Form\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Dojo_Form_Element_SubmitButton($fieldName);
  }

}