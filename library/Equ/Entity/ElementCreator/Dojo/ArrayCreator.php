<?php
namespace Equ\Entity\ElementCreator\Dojo;

class ArrayCreator extends \Equ\Entity\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Dojo_Form_Element_FilteringSelect($fieldName);
  }

}