<?php
use
  Equ\Crud\AbstractController,
  modules\permission\plugins\CrudFormBuilder,
  Equ\Form\ElementCreator\Dojo;

class Permission_AdminController extends AbstractController {

  protected $useFilterForm = false;

  public function init() {
    parent::init();
    $mainFormBuilder = new CrudFormBuilder($this->getEntityManager());
    $mainFormBuilder->setElementCreatorFactory(new Dojo\Factory());
    $this
      ->setMainFormBuilder($mainFormBuilder);
    $this->setCrudService(new \services\RoleResource($this->getEntityClass()));
  }

  protected function getEntityClass() {
    return 'entities\RoleResource';
  }

}