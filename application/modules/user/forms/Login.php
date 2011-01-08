<?php
namespace modules\user\forms;

class Login extends \Zend_Form {

  public function init() {
    $name = new \Zend_Dojo_Form_Element_TextBox('name');
    $name
      ->setRequired()
      ->addValidator(new \Zend_Validate_NotEmpty())
      ->setLabel('user/form/login/name');

    $password = new \Zend_Dojo_Form_Element_PasswordTextBox('password');
    $password
      ->setRequired()
      ->addValidator(new \Zend_Validate_NotEmpty())
      ->setLabel('user/form/login/password');

    $login = new \Zend_Dojo_Form_Element_SubmitButton('login');
    $login
      ->setRequired()
      ->addValidator(new \Zend_Validate_NotEmpty())
      ->setLabel('user/form/login/login');

    $this
      ->addElement($name)
      ->addElement($password)
      ->addElement($login);
  }

}