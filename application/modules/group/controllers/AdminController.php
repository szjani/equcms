<?php
use Equ\Crud\Controller;
use modules\group\plugins\CrudFormBuilder;
use Equ\Entity\ElementCreator\Dojo;

class Group_AdminController extends Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  public function init() {
    parent::init();
    $mainFormBuilder = new CrudFormBuilder($this->getEntityManager());
    $mainFormBuilder->setElementCreatorFactory(new Dojo\Factory());
    $this
      ->setMainFormBuilder($mainFormBuilder)
      ->setFilterFormBuilder($mainFormBuilder);
  }

  protected function getService() {
    return $this->_helper->serviceContainer('usergroup');
  }

}