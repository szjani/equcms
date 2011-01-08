<?php
namespace plugins;

class UserEntityBuilder extends \Equ\DTO\EntityBuilder {

  public function preVisit() {
    $dto = $this->dto;
    if ($dto->getData('password')) {
      $entity = $this->getEntity();
      $entity->setPassword($dto->getValue('password'));
    }
  }

}