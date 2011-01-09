<?php
namespace modules\user\plugins;
use Equ\Entity\FormBuilder;

class UserFilterFormBuilder extends FormBuilder {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'passwordHash', 'role');

  public function postVisit() {
    $this->getForm()->getElement('f_activationCode')->setValue('');
  }

}