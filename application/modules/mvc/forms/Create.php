<?php
namespace modules\mvc\forms;
use
  Equ\Form\IMappedType,
  Equ\Form\IBuilder,
  Equ\Form\OptionFlags;

class Create implements IMappedType {
  
  public function buildForm(IBuilder $builder) {
    $builder
      ->add('module')
      ->add('controller')
      ->add('action')
      ->add('parent');
  }
  
  public function getObjectClass() {
    return 'entities\Mvc';
  }
}
