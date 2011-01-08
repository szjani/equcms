<?php
use Equ\Crud\Controller;
use plugins\MvcFormBuilder;
use Equ\Entity\ElementCreator\Dojo;

class Mvc_AdminController extends Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'resource');

  public function init() {
    parent::init();
    $mainFormBuilder = new MvcFormBuilder($this->getEntityManager());
    $mainFormBuilder->setElementCreatorFactory(new Dojo\Factory());
    $this->setMainFormBuilder($mainFormBuilder);
  }

  protected function getService() {
    return $this->_helper->serviceContainer('mvc');
  }

}