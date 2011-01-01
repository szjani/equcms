<?php
class Index_Form_User extends \Equ\DojoForm {

//  public function init() {
//    $visible = new Zend_Form_Element_Text('visible');
//    $visible->setLabel('Visible');
//    $this->addElement($visible);
//  }

//  protected $_model = 'Entity\User';

  /**
   *
   * @var User
   */
//  protected $user;
//
//  public function setUser(User $user) {
//    $this->user = $user;
//    return $this;
//  }
//
//  public function getUser() {
//    return $this->user;
//  }

//  public function init() {
//    $email = new Zend_Form_Element_Text('email');
//    $email
//      ->setRequired()
//      ->addValidator(new Zend_Validate_EmailAddress());
//
//    $password = new Zend_Form_Element_Password('password');
//    $password
//      ->setRequired();
//
//    $this
//      ->addElement($email)
//      ->addElement($password);
//  }

//  public function isValid($data) {
//    $res = parent::isValid($data);
//    if ($res) {
//      foreach ($this as $element) {
//        $method = 'set' . ucfirst($element->getName());
//        if (method_exists($this->user, $method)) {
//          $this->user->$method($element->getValue());
//        }
//      }
//    }
//    return $res;
//  }

}