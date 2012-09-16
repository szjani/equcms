<?php
namespace modules\permission\forms;

use Equ\Form\IMappedType;
use Equ\Form\IBuilder;
use Equ\Form\OptionFlags;
use entities\RoleResource;

class Create implements IMappedType
{
    public function buildForm(IBuilder $builder)
    {
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

    public function getObjectClass()
    {
        return RoleResource::className();
    }

}
