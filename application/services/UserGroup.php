<?php
namespace services;
use Equ\Crud\Service;
use plugins\UserGroupFormBuilder;

class UserGroup extends Service {
	
	/**
   * @see Equ\Crud.Service::getEntityClass()
   */
  public function getEntityClass() {
    return 'entities\UserGroup';
  }

  public function __construct() {
    $formBuilder = new UserGroupFormBuilder($this->getEntityManager());
    $formBuilder->setElementCreatorFactory(new \Equ\Entity\ElementCreator\Dojo\Factory());
    $this->setMainFormBuilder($formBuilder);
  }
  
  
}