<?php
namespace services;
use Equ\Crud\Service;

class RoleResource extends Service {

  public function __construct($entityClass) {
    parent::__construct($entityClass);
    $this->setEntityBuilder(new RoleResourceEntityBuilder($this->getEntityManager(), $entityClass));
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