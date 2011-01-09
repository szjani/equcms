<?php
namespace Equ;

/**
 * DTO class
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class DTO implements \IteratorAggregate, \ArrayAccess, \Countable {

  /**
   * @var array
   */
  public $data = array();

  /**
   * @param array $data
   * @return DTO
   */
  public function fromArray(array $data) {
    foreach ($data as $key => $value) {
      $this->setData($value, $key);
    }
    return $this;
  }

  /**
   * @param \Traversable $object
   * @return DTO
   */
  public function fromTraversable(\Traversable $object) {
    foreach ($object as $key => $value) {
      $this->setData($value, $key);
    }
    return $this;
  }

  /**
   * @return \ArrayIterator
   */
  public function getIterator() {
    return new \ArrayIterator($this->data);
  }

  /**
   * @return int
   */
  public function count() {
    return count($this->data);
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getData($key) {
    if ($this->hasData($key)) {
      return $this->data[$key];
    }
    return null;
  }

  /**
   * @param mixed $value
   * @param string $key
   * @return DTO
   */
  public function setData($value, $key = null) {
    if ($key === null) {
      $this->data[] = $value;
    } else {
      $this->data[(string)$key] = $value;
    }
    return $this;
  }

  /**
   * @param string $key
   * @return DTO
   */
  public function removeData($key) {
    if ($this->hasData($key)) {
      unset($this->data[(string)$key]);
    }
    return $this;
  }

  /**
   * @return DTO
   */
  public function clearData() {
    unset($this->data);
    $this->data = array();
    return $this;
  }
  
  /**
   * @param string $key
   * @return boolean
   */
  public function hasData($key) {
    return \array_key_exists((string)$key, $this->data);
  }

  public function offsetGet($offset) {
    return $this->getData($offset);
  }

  public function offsetSet($offset, $value) {
    $this->setData($value, $offset);
  }

  public function offsetUnset($offset) {
    $this->removeData($offset);
  }

  public function offsetExists($offset) {
    return $this->hasData($offset);
  }

  /**
   * @param DTOVisitor $visitor
   */
  public function accept(DTOVisitor $visitor) {
    $visitor->visitDTO($this);
  }
  
}