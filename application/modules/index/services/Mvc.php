<?php
namespace modules\index\services;
use Equ\Crud\Service;
use modules\index\plugins\MvcFormBuilder;
use Equ\Entity\ElementCreator\Dojo;

class Mvc extends Service {

  public function getEntityClass() {
    return 'Entity\Mvc';
  }

  public function __construct() {
    $mainFormBuilder = new MvcFormBuilder($this->getEntityManager());
    $mainFormBuilder->setElementCreatorFactory(new Dojo\Factory());
    $this->setMainFormBuilder($mainFormBuilder);
  }

}