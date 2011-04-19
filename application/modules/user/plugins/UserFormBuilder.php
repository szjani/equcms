<?php
namespace modules\user\plugins;
use Equ\Entity\FormBuilder;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    modules
 * @package     user
 * @subpackage  plugins
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class UserFormBuilder extends FormBuilder {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'passwordHash', 'activationCode', 'role');

  /**
   * Add password field to form
   */
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