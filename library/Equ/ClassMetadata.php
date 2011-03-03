<?php
namespace Equ;

class ClassMetadata implements \IteratorAggregate {

  public $properties = array();

  /**
   * @param string $property
   * @param \Zend_Validate $validate
   */
  public function addPropertyValidator($property, \Zend_Validate $validate) {
    if (!\array_key_exists($property, $this->properties)) {
      $this->properties[$property] = array();
    }
    $this->properties[$property][] = $validate;
    return $this;
  }

  public function getIterator() {
    return new \ArrayIterator($this->properties);
  }
}