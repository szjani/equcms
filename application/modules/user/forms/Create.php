<?php
namespace modules\user\forms;
use
  Equ\Form\IMappedType,
  Equ\Form\IBuilder,
  Equ\Form\OptionFlags,
  entities\User;

class Create implements IMappedType {
  
  public function buildForm(IBuilder $builder) {
    $builder
      ->add('email')
      ->add('password', 'password')
      ->add('parent');
  }
  
  public function getObjectClass() {
    return User::className();
  }
}
