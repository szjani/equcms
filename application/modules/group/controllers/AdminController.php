<?php
use
  Equ\Crud\AbstractController,
  modules\group\plugins\CrudFormBuilder;

class Group_AdminController extends AbstractController {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  public function init() {
    parent::init();
    $mainFormBuilder = $this->getMainFormBuilder();
    $mainFormBuilder
      ->setIgnoredFields($this->ignoredFields);
    $this
      ->setMainFormBuilder($mainFormBuilder)
      ->setFilterFormBuilder($mainFormBuilder);
  }

  protected function getEntityClass() {
    return 'entities\UserGroup';
  }

}