<?php
use Equ\Crud\AbstractController;
use
  modules\group\forms\Create as CreateForm,
  modules\group\forms\Filter as FilterForm;

class Group_AdminController extends AbstractController {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  public function getFilterForm() {
    return new FilterForm();
  }

  public function getMainForm() {
    return new CreateForm();
  }


}