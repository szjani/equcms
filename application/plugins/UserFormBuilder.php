<?php
namespace plugins;
use Equ\Entity\FormBuilder;

class UserFormBuilder extends FormBuilder {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'passwordHash', 'activationCode');

  public function postVisit() {
    $password = new \Zend_Dojo_Form_Element_PasswordTextBox('password');
    $password
      ->addValidator(new \Zend_Validate_NotEmpty())
      ->addValidator(new \Zend_Validate_StringLength(array('min' => 8)))
      ->setRequired()
      ->setLabel('entities/User/password')
      ->setOrder(1);
    $this->getForm()->addElement($password);
  }
  
}