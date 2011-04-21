<?php
namespace Equ\DTO;
use
  Equ\AbstractEntityBuilder,
  Equ\DTO\Exception\RuntimeException;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class EntityBuilder extends AbstractEntityBuilder implements IDTOVisitor {

  /**
   * @var DTO
   */
  protected $dto = null;

  /**
   * @param \Equ\DTO $dto
   */
  public function visitDTO(\Equ\DTO $dto) {
    $this->dto = $dto;
    $this->preVisit();
    $metadata = $this->entityManager->getClassMetadata(\get_class($this->getEntity()));

    foreach ($dto->getIterator() as $name => $value) {
      $setterMethod = 'set' . \ucfirst($name);
      if (\array_key_exists($name, $metadata->fieldMappings) && \method_exists($this->getEntity(), $setterMethod)) {
        $this->getEntity()->$setterMethod($value);
      } elseif (\array_key_exists($name, $metadata->associationMappings)) {
        if (\method_exists($this->getEntity(), $setterMethod)) {
          $targetEntity = null;
          if ('0' != $value) {
            $relatedClass = $metadata->associationMappings[$name]['targetEntity'];
            $targetEntity = $this->entityManager->getReference($relatedClass, $value);
            if (!isset($targetEntity)) {
              throw new RuntimeException("Invalid '$relatedClass' id: $value");
            }
          }
          $this->getEntity()->$setterMethod($targetEntity);
        }
      }
    }
    $this->postVisit();
  }
}