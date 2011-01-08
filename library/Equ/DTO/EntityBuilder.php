<?php
namespace Equ\DTO;
use Equ\DTOVisitor;
use Doctrine\ORM\EntityManager;
use Equ\Entity;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class EntityBuilder implements DTOVisitor {

  /**
   * @var DTO
   */
  protected $dto = null;

  /**
   * @var Entity
   */
  private $entity;

  /**
   * @var EntityManager
   */
  private $entityManager;

  /**
   * @var string
   */
  private $entityClass;

  /**
   * @param EntityManager $em
   * @param Entity $entity
   */
  public function __construct(EntityManager $em, $entityClass) {
    $this->entityClass = $entityClass;
    $this->entityManager = $em;
  }

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

  protected function preVisit() {}
  protected function postVisit() {}

  /**
   * @param DTO $dto
   */
  public function visitDTO(DTO $dto) {
    $this->dto = $dto;
    $this->preVisit();
    $metadata = $this->entityManager->getClassMetadata(\get_class($this->getEntity()));

    foreach ($dto->getIterator() as $name => $value) {
      $setterMethod = 'set' . \ucfirst($name);
      if (\array_key_exists($name, $metadata->fieldMappings) && \method_exists($this->getEntity(), $setterMethod)) {
        $this->getEntity()->$setterMethod($value);
      } elseif (\array_key_exists($name, $metadata->associationMappings)) {
        $targetEntity = $this->entityManager
          ->getRepository($metadata->associationMappings[$name]['targetEntity'])->find($value);
        if (isset($targetEntity) && \method_exists($this->getEntity(), $setterMethod)) {
          $this->getEntity()->$setterMethod($targetEntity);
        }
      }
    }
    $this->postVisit();
  }
}