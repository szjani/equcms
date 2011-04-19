<?php
namespace Equ;

class ClassMetadata implements \IteratorAggregate {

  public $properties = array();

  /**
   * @param string $property
   * @param \Zend_Validate $validate
   */
  public function addPropertyValidator($property, \Zend_Validate_Abstract $validate) {
    if (!\array_key_exists($property, $this->properties)) {
      $this->properties[$property] = array();
    }
    $this->properties[$property][] = $validate;
    return $this;
  }

  /**
   *
   * @param string $property
   * @return array
   */
  public function getPropertyValidators($property) {
    if (!array_key_exists($property, $this->properties)) {
      return array();
    }
    return $this->properties[$property];
  }

  public function getIterator() {
    return new \ArrayIterator($this->properties);
  }
}