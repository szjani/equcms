<?php
namespace Equ\Entity\ElementCreator\Dojo;

class StringCreator extends \Equ\Entity\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    return new \Zend_Dojo_Form_Element_ValidationTextBox($fieldName);
  }

  public function createElement($fieldName, array $values = array()) {
    parent::createElement($fieldName, $values);
    if ($this->isUsedPlaceHolders()) {
      $this->element->setDijitParam('placeHolder', \Zend_Form::getDefaultTranslator()->translate($this->getPlaceHolder()));
    }
    return $this->element;
  }
  
  public function addValidator(\Zend_Form_Element $element, \Zend_Validate_Abstract $validator) {
    parent::addValidator($element, $validator);
    if ($this->isUsedDefaultValidators()) {
      if ($validator instanceof \Zend_Validate_EmailAddress) {
        $element->setRegExp('^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+');
      }
      if ($validator instanceof \Zend_Validate_Regex) {
        $element->setRegExp($validator->getPattern());
      }
    }
  }

}