<?php
namespace modules\group\forms;

use Equ\Form\IMappedType;
use Equ\Form\IBuilder;
use Equ\Form\OptionFlags;

class Filter extends Create
{
    public function buildForm(IBuilder $builder)
    {
        $builder->setOptionFlags(new OptionFlags(
            OptionFlags::ALL & ~OptionFlags::IMPLICIT_VALIDATORS & ~OptionFlags::EXPLICIT_VALIDATORS
        ));

        $builder
            ->add('name')
            ->add('parent');
    }

}
