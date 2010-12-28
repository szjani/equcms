<?php
namespace Equ\Entity\ElementCreator\Dojo;

class SubmitCreator extends \Equ\Entity\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Dojo_Form_Element_SubmitButton($fieldName);
  }

}