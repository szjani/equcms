<?php
namespace Equ;
use Doctrine\ORM\EntityManager;

abstract class AbstractEntityBuilder {

  /**
   * @var \Equ\Entity
   */
  protected $entity;

  /**
   * @var EntityManager
   */
  protected $entityManager;

  /**
   * @var string
   */
  protected $entityClass;

  /**
   * @param EntityManager $em
   * @param string $entityClass
   */
  public function __construct(EntityManager $em, $entityClass) {
    $this->entityManager = $em;
    $this->entityClass = $entityClass;
  }

  public function preVisit() {}
  public function postVisit() {}

  /**
   * @return \Equ\Entity
   */
  public function getEntity() {
    if ($this->entity === null) {
      $entity = $this->entityManager->getClassMetadata($this->entityClass)->newInstance();
      if ($entity instanceof \Equ\Entity\FormBase) {
        $entity->init();
      }
      $this->entity = $entity;
    }
    return $this->entity;
  }

  /**
   * @param \Equ\Entity $entity
   * @return AbstractEntityBuilder
   */
  public function setEntity(\Equ\Entity $entity) {
    $this->entity = $entity;
    return $this;
  }

}