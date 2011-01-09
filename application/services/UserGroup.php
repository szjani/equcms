<?php
namespace services;
use Equ\Crud\Service;

class UserGroup extends Service {
	
	/**
   * @see Equ\Crud.Service::getEntityClass()
   */
  public function getEntityClass() {
    return 'entities\UserGroup';
  }

}