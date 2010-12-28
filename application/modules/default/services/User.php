<?php
use Equ\Crud\Service;

class Default_Service_UserEntityBuilder extends \Equ\Form\EntityBuilder {

  public function visitForm(\Zend_Form $form) {
    parent::visitForm($form);
    if ($form->getValue('password')) {
      $entity = $this->getEntity();
      $entity->setPassword($form->getValue('password'));
      $entity->setActivationCode($entity->generateString(12));
    }
  }

}

class Default_Service_User extends Service {

  public function getEntityClass() {
    return 'Entity\User';
  }

  public function __construct() {
    $formBuilder = new Default_Form_UserBuilder($this->getEntityManager());
    $formBuilder->setElementCreatorFactory(new \Equ\Entity\ElementCreator\Dojo\Factory());
    $this
      ->setMainFormBuilder($formBuilder)
      ->setEntityBuilder(new Default_Service_UserEntityBuilder($this->getEntityManager(), $this->getEntityClass()));
  }

  public function createEmpty