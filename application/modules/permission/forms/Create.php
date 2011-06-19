<?php
namespace modules\permission\forms;
use
  Equ\Form\IMappedType,
  Equ\Form\IBuilder,
  Equ\Form\OptionFlags;

class Create implements IMappedType {
  
  public function buildForm(IBuilder $builder) {
    $builder
      ->add('role')
      ->add('resource')
      ->add('allowed', 'boolean')
      ->add('privilege');
  }
  
  public function getObjectClass() {
    return 'entities\RoleResource';
  }
}
