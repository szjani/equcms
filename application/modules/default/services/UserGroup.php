<?php
use Equ\Crud\Service;

class Default_Service_UserGroup extends Service {
	
	/**
   * @see Equ\Crud.Service::getEntityClass()
   */
  public function getEntityClass() {
    return 'Entity\UserGroup';
  }

  public function __construct() {
    $formBuilder = new Default_Plugin_UserGroupFormBuilder($this->getEntityManager());
    $formBuilder->setElementCreatorFactory(new \Equ\Entity\ElementCreator\Dojo\Factory());
    $this->setMainFormBuilder($formBuilder);
  }
  
  
}