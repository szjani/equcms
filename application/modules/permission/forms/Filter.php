<?php
namespace modules\permission\forms;
use
  Equ\Form\IMappedType,
  Equ\Form\IBuilder,
  Equ\Form\OptionFlags;

class Filter extends Create {
  
  public function buildForm(IBuilder $builder) {
    $builder->setOptionFlags(new OptionFlags(
       OptionFlags::ALL & ~OptionFlags::IMPLICIT_VALIDATORS & ~OptionFlags::EXPLICIT_VALIDATORS
    ));
    
   $builder
      ->add('role', 'string')
      ->add('resource')
      ->add('privilege')
      ->add('allowed');
   
   $role = $builder->getForm()->getElement('role');
   $role
    ->setAttrib('data-provide', 'typeaheadajax')
    ->setAttrib('data-source', '/admin/role/lookup/format/json/q/')
    ->setAttrib('data-findone', '/admin/role/lookup/format/json/id/');
  }
}
