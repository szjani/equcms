<?php
use
  Equ\Crud\AbstractController,
  modules\permission\plugins\CrudFormBuilder;

class Permission_AdminController extends AbstractController {

  protected $useFilterForm = false;

  public function init() {
    parent::init();
    $mainFormBuilder = new CrudFormBuilder(
        $this->getEntityManager(),
        $this->_helper->serviceContainer('form.elementcreator.factory')
    );
    $mainFormBuilder->setIgnoredFields(array('role'));
    $this
      ->setMainFormBuilder($mainFormBuilder)
      ->setCrudService(new \services\RoleResource($this->getEntityClass()));
  }

  protected function getEntityClass() {
    return 'entities\RoleResource';
  }

}