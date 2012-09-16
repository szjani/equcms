<?php
namespace modules\user\forms;

use Equ\Form\IMappedType;
use Equ\Form\IBuilder;
use Equ\Form\OptionFlags;

class Update extends Create
{
    public function buildForm(IBuilder $builder)
    {
        $builder
            ->add('email')
            ->add('userGroup');
    }

}
