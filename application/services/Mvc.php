<?php
namespace services;
use Equ\Crud\Service;

class Mvc extends Service {

  public function getEntityClass() {
    return 'entities\Mvc';
  }

}