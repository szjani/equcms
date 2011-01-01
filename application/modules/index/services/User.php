<?php
use Equ\Crud\Service;

class Index_Service_UserEntityBuilder extends \Equ\Form\EntityBuilder {

  public function preVisit() {
    $form = $this->form;
    if ($form->getValue('password')) {
      $entity = $this->getEntity();
      $entity->setPassword($form->getValue('password'));
    }
  }

}

class Index_Service_User extends Service {

  public function getEntityClass() {
    return 'Entity\User';
  }
  
public function getMainForm($id = null, $refresh = false) {
    $form = parent::getMainForm($id, $refresh);
    if ($id !== null) {
      $form->removeElement('password');
    }
    return $form;
  }

  public function __construct() {
    $formBuilder = new Index_Plugin_UserFormBuilder($this->getEntityManager());
    $formBuilder->setElementCreatorFactory(new \Equ\Entity\ElementCreator\Dojo\Factory());
    $this
      ->setMainFormBuilder($formBuilder)
      ->setEntityBuilder(new Index_Service_UserEntityBuilder($this->getEntityManager(), $this->getEntityClass()));
  }

//  public function createEmptyForm() {
//    return new Default_Form_User();
//  }

}