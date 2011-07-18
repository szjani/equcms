<?php
namespace modules\group\forms;
use
  Equ\Form\IMappedType,
  Equ\Form\IBuilder,
  Equ\Form\OptionFlags,
  entities\UserGroup;

class Create implements IMappedType {
  
  public function buildForm(IBuilder $builder) {
    $builder
      ->add('name')
      ->add('parent');
  }
  
  public function getObjectClass() {
    return UserGroup::className();
  }
}
