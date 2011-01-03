<?php
namespace services;
use Equ\Crud\Service;
use Equ\Entity\ElementCreator\Dojo;
use plugins\RoleResourceFormBuilder;

class RoleResource extends Service {
	/**
   * @see Equ\Crud.Service::getEntityClass()
   */
  public function getEntityClass() {
    return 'entities\RoleResource';
  }

  public function __construct() {
    $this->setEntityBuilder(new RoleResourceEntityBuilder($this->getEntityManager(), $this->getEntityClass()));
    $this->setMainFormBuilder(new RoleResourceFormBuilder($this->getEntityManager()));
    $this->getMainFormBuilder()->setElementCreatorFactory(new Dojo\Factory());
  }
}

class RoleResourceEntityBuilder extends \Equ\Form\EntityBuilder {

  public function preVisit() {
    $form = $this->form;
    if (\array_key_exists('allowed', $form->getValues())) {
      $entity = $this->getEntity();
      /* @var $entity \entities\RoleResource */
      $entity->setAllow($form->getValue('allowed'));
    }
  }

}