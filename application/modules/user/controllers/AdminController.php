<?php
use Equ\Crud\AbstractController;
use modules\user\forms\Create as CreateForm;
use modules\user\forms\Update as UpdateForm;
use modules\user\forms\Filter as FilterForm;

class User_AdminController extends AbstractController
{
    protected $ignoredFields = array('lft', 'rgt', 'lvl');

//  protected $useFilterForm = false;

    public function getFilterForm()
    {
        return new FilterForm();
    }

    public function getMainForm()
    {
        return new CreateForm();
    }

    public function getUpdateForm()
    {
        return new UpdateForm();
    }

}