<?php
namespace modules\mvc\forms;

use Equ\Form\IMappedType;
use Equ\Form\IBuilder;
use Equ\Form\OptionFlags;
use entities\Mvc;

class Create implements IMappedType
{
    public function buildForm(IBuilder $builder)
    {
        $builder
            ->add('module')
            ->add('controller')
            ->add('action')
            ->add('parent');
    }

    public function getObjectClass()
    {
        return Mvc::className();
    }

}
