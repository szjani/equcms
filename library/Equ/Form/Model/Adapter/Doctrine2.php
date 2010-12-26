<?php
namespace Equ\Form\Model\Adapter;
use Doctrine\ORM\EntityManager;

class Doctrine2 implements \ZFDoctrine_Form_Model_Adapter_Interface {

  /**
   * @var EntityManager
   */
  private $entityManager;

  private $entity;

  private $entityType;

  /**
   * @return EntityManager
   */
  public function getEntityManager() {
    return $this->entityManager;
  }

  /**
   * @param EntityManager $entityManager
   * @return Doctrine2
   */
  public function setEntityManager(EntityManager $entityManager) {
    $this->entityManager = $entityManager;
    return $this;
  }

  
  public function addManyRecord($name, $record) {

  }

  public function deleteRecord($record) {
    $this->entityManager->remove($record);
  }

  public function getAllRecords($class) {
    $this->entityManager->getRepository($this->entityType)->findAll();
  }

  public function getColumns() {
    return $this->entityManager->getClassMetadata($this->entityType)->columnNames;
  }

  public function getManyRelations() {
    return array();
  }

  public function getNewRecord() {
    return $this->entityManager->getClassMetadata($this->entityType)->newInstance();
  }

  public function getRecord() {
    return $this->entity;
  }

  public function getRecordIdentifier($record) {
    $values = $this->entityManager->getClassMetadata(\get_class($this->entity))->getIdentifierValues($record);
    return $values[0];
  }

  public function getRecordValue($column) {
    return $this->entityManager->getClassMetadata($this->entityType)->getFieldValue($this->entity, $column);
  }

  public function getRelatedRecordId($record, $name) {
    return null;
  }

  public function getRelatedRecords($name) {
    return array();
  }

  public function getTable() {

  }

  public function saveRecord() {
    $this->entityManager->persist($this->entity);
  }

  public function setRecord($instance) {
    $this->entity = $instance;
  }

  public function setRecordValues($values) {
    foreach ($values as $key => $value) {
      $this->entityManager->getClassMetadata($this->entityType)->setFieldValue($this->entity, $key, $value);
    }
  }

  public function setTable($table) {

  }

}