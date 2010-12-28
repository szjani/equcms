<?php
namespace Equ\Entity\ElementCreator\Dojo;

class StringCreator extends \Equ\Entity\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Dojo_Form_Element_ValidationTextBox($fieldName);
  }
  
  public function addValidator(\Zend_Form_Element $element, \Zend_Validate_Abstract $validator) {
    if ($validator instanceof \Zend_Validate_EmailAddress) {
      $element->setRegExp('^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+');
    }
  }

}