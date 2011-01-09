<?php
namespace services;
use Equ\Crud\Service;

class RoleResource extends Service {
	/**
   * @see Equ\Crud.Service::getEntityClass()
   */
  public function getEntityClass() {
    return 'entities\RoleResource';
  }

  public function __construct() {
    $this->setEntityBuilder(new RoleResourceEntityBuilder($this->getEntityManager(), $this->getEntityClass()));
  }
}

class RoleResourceEntityBuilder extends \Equ\DTO\EntityBuilder {

  public function preVisit() {
    $dto = $this->dto;
    if ($dto->hasData('allowed')) {
      $entity = $this->getEntity();
      /* @var $entity \entities\RoleResource */
      $entity->setAllow($dto->getData('allowed'));
    }
  }

}