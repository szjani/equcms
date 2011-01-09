<?php
namespace plugins;

class UserEntityBuilder extends \Equ\DTO\EntityBuilder {

  public function preVisit() {
    $dto = $this->dto;
    if ($dto->hasData('password')) {
      $this->getEntity()->setPassword($dto->getData('password'));
    }
  }

}