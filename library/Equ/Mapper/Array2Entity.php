<?php
namespace Equ\Mapper;
use Doctrine\ORM\EntityManager;

class Array2Entity extends Array2Object {
  
  /**
   * @var EntityManager
   */
  private $entityManager;
  
  /**
   * @param array $values
   * @param object $object
   * @param EntityManager $em 
   */
  public function __construct(array $values, $object, EntityManager $em) {
    parent::__construct($values, $object);
    $this->entityManager = $em;
  }
  
  public function convert() {
    $metadata = $this->entityManager->getClassMetadata(get_class($this->resultObject));
    foreach ($this->values as $key => $value) {
      $setterMethod = $this->getSetterMethod($key);
      if (method_exists($this->resultObject, $setterMethod)) {
        if (is_numeric($value) && array_key_exists($key, $metadata->associationMappings)) {
          $targetEntity = null;
          if ('0' != $value) {
            $relatedClass = $metadata->associationMappings[$key]['targetEntity'];
            $targetEntity = $this->entityManager->getReference($relatedClass, $value);
            if (!isset($targetEntity)) {
              throw new RuntimeException("Invalid '$relatedClass' id: $value");
            }
            $this->resultObject->$setterMethod($targetEntity);
          }
        } else {
          $this->resultObject->$setterMethod($value);
        }
      }
    }
    return $this->resultObject;
  }
  
}
