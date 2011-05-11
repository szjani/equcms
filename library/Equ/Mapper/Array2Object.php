<?php
namespace Equ\Mapper;

class Array2Object implements IMapper {
  
  /**
   * @var array
   */
  protected $values = null;

  /**
   * @var object
   */
  protected $resultObject = null;

  /**
   * @param array $values
   * @param object $object 
   */
  public function __construct(array $values, $object) {
    $this
      ->setValues($values)
      ->setObject($object);
  }
  
  /**
   * @param array $values
   * @return Array2Object 
   */
  public function setValues(array $values) {
    $this->values = $values;
    return $this;
  }

  /**
   * @param object $object
   * @return Array2Object 
   */
  public function setObject($object) {
    if (!is_object($object)) {
      throw new \InvalidArgumentException('$object has to be an object');
    }
    $this->resultObject = $object;
    return $this;
  }
  
  /**
   * @return object
   */
  public function convert() {
    foreach ($this->values as $key => $value) {
      $method = $this->getSetterMethod($key);
      if (method_exists($this->resultObject, $method)) {
        $this->resultObject->$method($value);
      }
    }
    return $this->resultObject;
  }

  /**
   * @param string $key
   * @return string
   */
  private function getSetterMethod($key) {
    return 'set' . ucfirst($key);
  }
  
}