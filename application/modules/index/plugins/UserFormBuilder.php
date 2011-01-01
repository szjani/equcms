<?php
use Equ\Entity\FormBuilder;

class Index_Plugin_UserFormBuilder extends FormBuilder {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'passwordHash', 'activationCode');

  public function postVisit() {
    $password = new Zend_Dojo_Form_Element_PasswordTextBox('password');
    $password
      ->addValidator(new Zend_Validate_NotEmpty())
      ->addValidator(new Zend_Validate_StringLength(array('min' => 8)))
      ->setRequired()
      ->setLabel('Entity/User/password')
      ->setOrder(1);
    $this->getForm()->addElement($password);
  }
  
}