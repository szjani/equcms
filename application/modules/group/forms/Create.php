<?php
namespace modules\group\forms;

use Equ\Form\IMappedType;
use Equ\Form\IBuilder;
use Equ\Form\OptionFlags;
use entities\UserGroup;

class Create implements IMappedType
{
    public function buildForm(IBuilder $builder)
    {
        $builder
            ->add('name')
            ->add('parent');
    }

    public function getObjectClass()
    {
        return UserGroup::className();
    }

}
