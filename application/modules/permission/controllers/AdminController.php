<?php
use
  Equ\Crud\AbstractController,
  modules\permission\forms\Create as CreateForm,
  modules\permission\forms\Filter as FilterForm;

class Permission_AdminController extends AbstractController {

  protected $useFilterForm = true;

//  public function init() {
//    parent::init();
//    $mainFormBuilder = new CrudFormBuilder(
//        $this->getEntityManager(),
//        $this->_helper->serviceContainer('form.elementcreator.factory')
//    );
//    $mainFormBuilder->setIgnoredFields(array('role'));
//    $this
//      ->setMainFormBuilder($mainFormBuilder)
//      ->setCrudService(new \services\RoleResource($this->getEntityClass()));
//  }

//  protected function getEntityClass() {
//    return 'entities\RoleResource';
//  }
  public function getFilterForm() {
    return new FilterForm();
  }

  public function getMainForm() {
    return new CreateForm();
  }

}