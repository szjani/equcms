<?php
use
  Equ\Crud\AbstractController,
  modules\group\plugins\CrudFormBuilder,
  Equ\Form\ElementCreator\Dojo;

class Group_AdminController extends AbstractController {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  public function init() {
    parent::init();
    $mainFormBuilder = new CrudFormBuilder($this->getEntityManager());
    $mainFormBuilder->setElementCreatorFactory(new Dojo\Factory());
    $this
      ->setMainFormBuilder($mainFormBuilder)
      ->setFilterFormBuilder($mainFormBuilder);
  }

  protected function getEntityClass() {
    return 'entities\UserGroup';
  }

}