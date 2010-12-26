<?php
class Default_Form_UserBuilder extends Equ\Entity\FormBuilder {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'passwordHash', 'activationCode');

  protected $fieldLabels   = array('email' => 'E-mail');

  public function postVisit() {
    $password = new Zend_Form_Element_Password('password');
    $password
      ->addValidator(new Zend_Validate_NotEmpty())
      ->addValidator(new Zend_Validate_StringLength(array('min' => 8)))
      ->setRequired()
      ->setLabel('Password')
      ->setOrder(1);
    $this->getForm()->addElement($password);
  }
  
}