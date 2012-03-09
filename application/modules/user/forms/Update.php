<?php
namespace modules\user\forms;
use
  Equ\Form\IMappedType,
  Equ\Form\IBuilder,
  Equ\Form\OptionFlags;

class Update extends Create {
  
  public function buildForm(IBuilder $builder) {
    $builder
      ->add('email')
      ->add('userGroup');
  }
}
