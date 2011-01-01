<?php
namespace services;
use Equ\Crud\Service;
use plugins\UserFormBuilder;

class UserEntityBuilder extends \Equ\Form\EntityBuilder {

  public function preVisit() {
    $form = $this->form;
    if ($form->getValue('password')) {
      $entity = $this->getEntity();
      $entity->setPassword($form->getValue('password'));
    }
  }

}

class User extends Service {

  public function getEntityClass() {
    return 'entities\User';
  }
  
public function getMainForm($id = null, $refresh = false) {
    $form = parent::getMainForm($id, $refresh);
    if ($id !== null) {
      $form->removeElement('password');
    }
    return $form;
  }

  public function __construct() {
    $formBuilder = new UserFormBuilder($this->getEntityManager());
    $formBuilder->setElementCreatorFactory(new \Equ\Entity\ElementCreator\Dojo\Factory());
    $this
      ->setMainFormBuilder($formBuilder)
      ->setEntityBuilder(new UserEntityBuilder($this->getEntityManager(), $this->getEntityClass()));
  }

//  public function createEmptyForm() {
//    return new Default_Form_User();
//  }

}