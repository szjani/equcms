<?php
namespace modules\user\forms;

/**
 * Login form
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    modules
 * @package     user
 * @subpackage  forms
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Login extends \Zend_Form {

  public function init() {
    $email = new \Zend_Dojo_Form_Element_TextBox('email');
    $email
      ->setRequired(true)
      ->addValidator(new \Zend_Validate_NotEmpty())
      ->setLabel('user/form/login/email');

    $password = new \Zend_Dojo_Form_Element_PasswordTextBox('password');
    $password
      ->setRequired(true)
      ->addValidator(new \Zend_Validate_NotEmpty())
      ->setLabel('user/form/login/password');

    $login = new \Zend_Dojo_Form_Element_SubmitButton('login');
    $login
      ->setRequired(true)
      ->addValidator(new \Zend_Validate_NotEmpty())
      ->setLabel('user/form/login/login');

    $this
      ->addElement($email)
      ->addElement($password)
      ->addElement($login);
  }

}