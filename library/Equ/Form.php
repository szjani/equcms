<?php
namespace Equ;
use
  Equ\Form\IFormVisitor,
  Equ\Form\IVisitable;

/**
 * Base form class
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Equ
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Form extends \Zend_Form implements IVisitable {

  /**
   * @var mixed
   */
  private $data = null;

  /**
   * @param FormVisitor $visitor
   */
  public function accept(IFormVisitor $visitor) {
    $visitor->visitForm($this);
  }

  /**
   * @param mixed $data
   * @return Form
   */
  public function setData($data) {
    $this->data = $data;
    if (is_array($data)) {
      $this->setDefaults($data);
    } elseif (is_object($data)) {
      foreach ($data as $key => $value) {
        $this->setDefault($key, $value);
      }
    }
    return $this;
  }

  /**
   * @param mixed $data
   */
  public function bind($data = null) {
    if (null !== $data) {
      $this->setData($data);
    }
    if ($this->data instanceof Validatable) {
      $classMetadata = new ClassMetadata();
      $object = $this->data;
      $object::loadValidatorMetadata($classMetadata);
      foreach ($classMetadata as $key => $validators) {
        $this->getElement($key)->addValidators($validators);
      }
    }
  }

  /**
   * @return boolean
   */
  public function isValid($data) {
    $isValid = false;
    if ($data instanceof \Zend_Controller_Request_Http) {
      $isValid = parent::isValid($this->request->getParams());
    } else {
      $isValid = parent::isValid($data);
    }
    if ($isValid && $this->data instanceof Validatable) {
      Options::setOptions($this->data, $this->getValues());
    }
    return $isValid;
  }

}