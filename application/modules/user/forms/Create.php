<?php
namespace modules\user\forms;

use Equ\Form\IMappedType;
use Equ\Form\IBuilder;
use Equ\Form\OptionFlags;
use entities\User;

class Create implements IMappedType
{
    public function buildForm(IBuilder $builder)
    {
        $builder
            ->add('email')
            ->add('password', 'password')
            ->add('userGroup');

        $builder->getForm()->password->setRequired();
    }

    public function getObjectClass()
    {
        return User::className();
    }

}
