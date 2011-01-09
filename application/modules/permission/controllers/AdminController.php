<?php
use Equ\Crud\Controller;
use modules\permission\plugins\CrudFormBuilder;
use Equ\Entity\ElementCreator\Dojo;

class Permission_AdminController extends Controller {

  protected $useFilterForm = false;

  public function init() {
    parent::init();
    $mainFormBuilder = new CrudFormBuilder($this->getEntityManager());
    $mainFormBuilder->setElementCreatorFactory(new Dojo\Factory());
    $this
      ->setMainFormBuilder($mainFormBuilder);
//      ->setFilterFormBuilder($mainFormBuilder);
  }

  protected function getService() {
    return $this->_helper->serviceContainer('roleresource');
  }

}