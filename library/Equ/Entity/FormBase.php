<?php
namespace Equ\Entity;

interface FormBase extends Visitable {

  public function getFieldValidators($fieldName);
  
  public function clearFieldValidators($fieldName);
  
  public function addFieldValidator($fieldName, \Zend_Validate_Abstract $validator);
  
  public function init();

}