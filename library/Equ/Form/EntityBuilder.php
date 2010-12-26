<?php
namespace Equ\Form;
use Doctrine\ORM\EntityManager;

/**
 * Create entity from form
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class EntityBuilder implements \Equ\FormVisitor {

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

  /**
   * @return \Equ\Entity
   */
  public function getEntity() {
    if ($this->entity === null) {
      $this->entity = $this->entityManager->getClassMetadata($this->entityClass)->newInstance();
    }
    return $this->entity;
  }

  /**
   * @param \Equ\Entity $entity
   * @return EntityBuilder
   */
  public function setEntity(\Equ\Entity $entity) {
    $this->entity = $entity;
    return $this;
  }

  /**
   * @param \Zend_Form $form
   */
  public function visitForm(\Zend_Form $form) {
    $values = $form->getValues();
    $metadata = $this->entityManager->getClassMetadata(\get_class($this->getEntity()));

    foreach ($values as $name => $value) {
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
  }

}