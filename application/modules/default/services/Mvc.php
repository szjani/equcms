<?php
use Equ\Crud\Service;

class Default_Service_Mvc extends Service {

  public function getEntityClass() {
    return 'Entity\Mvc';
  }

  public function __construct() {
    $this->setMainFormBuilder(new Default_Plugin_MvcFormBuilder($this->getEntityManager()));
  }

}