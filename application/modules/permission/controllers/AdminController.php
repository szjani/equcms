<?php

use Equ\Crud\AbstractController;
use modules\permission\forms\Create as CreateForm;
use modules\permission\forms\Filter as FilterForm;

class Permission_AdminController extends AbstractController
{
    public function getFilterForm()
    {
        return new FilterForm();
    }

    public function getMainForm()
    {
        return new CreateForm();
    }

}