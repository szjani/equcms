<?php
namespace Equ\Mapper;

class Object2Array implements IMapper {
  
  /**
   * @var array
   */
  private $values = null;

  /**
   * @var object
   */
  private $inputObject = null;

  /**
   * @param object $object 
   * @param array $values
   */
  public function __construct($object, array $values = array()) {
    $this
      ->setValues($values)
      ->setObject($object);
  }
  
  /**
   * @param array $values
   * @return Object2Array 
   */
  public function setValues(array $values) {
    $this->values = $values;
    return $this;
  }

  /**
   * @param object $object
   * @return Object2Array 
   */
  public function setObject($object) {
    if (!is_object($object)) {
      throw new \InvalidArgumentException('$object has to be an object');
    }
    $this->inputObject = $object;
    return $this;
  }
  
  /**
   * @return object
   */
  public function convert() {
    foreach (get_class_methods($this->inputObject) as $method) {
      if ($this->isGetter($method)) {
        $this->values[$this->getKeyName($method)] = $this->inputObject->$method();
      }
    }
    return $this->values;
  }
  
  /**
   * @param string $method
   * @return boolean
   */
  public function isGetter($method) {
    return substr($method, 0, 3) == 'get';
  }

  /**
   * @param string $key
   * @return string
   */
  private function getKeyName($method) {
    return lcfirst(substr($method, 3));
  }

  
}