<?php
namespace Equ\Entity\ElementCreator;

/**
 * Abstract form element creator class.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
abstract class AbstractCreator {

  /**
   * @var array
   */
  protected $values;

  /**
   * @var string
   */
  protected $namespace = '';

  /**
   *
   * @var string
   */
  private $label = null;

  /**
   * @var string
   */
  private $placeholder = null;

  /**
   * @var string
   */
  private $usePlaceHolder = false;

  /**
   * @var boolean
   */
  private $useDefaultValidators = true;

  /**
   * @var array
   */
  private $validators = array();

  /**
   * @var \Zend_Form_Element
   */
  protected $element;

  /**
   * @param string $namespace
   */
  public function __construct($namespace) {
    $this->namespace = $namespace;
  }

  /**
   * @param \Zend_Form_Element $element
   * @param \Zend_Validate_Abstract $validator
   * @return AbstractCreator
   */
  public function addValidator(\Zend_Form_Element $element, \Zend_Validate_Abstract $validator) {
    $element->addValidator($validator);
    return $this;
  }

  /**
   * @param string $label
   * @return AbstractCreator
   */
  public function setLabel($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * @return string
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * @param string $string
   * @return AbstractCreator
   */
  public function setPlaceHolder($string) {
    $this->placeholder = $string;
    return $this;
  }

  /**
   * @return string
   */
  public function getPlaceHolder() {
    return $this->placeholder;
  }

  /**
   * @param boolean $use
   * @return AbstractCreator
   */
  public function usePlaceHolders($use = true) {
    $this->usePlaceHolder = (boolean)$use;
    return $this;
  }

  /**
   * @return boolean
   */
  public function isUsedPlaceHolders() {
    return $this->usePlaceHolder;
  }

  /**
   * @param array $validators
   * @return AbstractCreator
   */
  public function setValidators(array $validators) {
    $this->validators = $validators;
    return $this;
  }

  /**
   * @return array
   */
  public function getValidators() {
    return $this->validators;
  }
  
  /**
   * @param boolean $use
   * @return AbstractCreator
   */
  public function useDefaultValidators($use = true) {
    $this->useDefaultValidators = (boolean)$use;
    return $this;
  }

  /**
   * @return boolean
   */
  public function isUsedDefaultValidators() {
    return $this->useDefaultValidators;
  }
  
  /**
   * @param \Zend_Form_Element $element
   */
  protected function addDefaultValidators(\Zend_Form_Element $element) {
    if (array_key_exists('nullable', $this->values) && !$this->values['nullable']) {
      $element->setRequired();
      $this->addValidator($element, new \Zend_Validate_NotEmpty());
    }
    if (array_key_exists('length', $this->values) && \is_numeric($this->values['length'])) {
      $validator = new \Zend_Validate_StringLength();
      $validator->setMax($this->values['length']);
      $this->addValidator($element, $validator);
    }
  }

  /**
   * You should pass $values from
   * $em->getClassMetadata($className)->fieldMappings
   *
   * @param string $fieldName
   * @param array $values field mapping array of entity
   * @return \Zend_Form_Element
   */
  public function createElement($fieldName, array $values = array()) {
    $this->values = $values;
    $this->element = $this->buildElement($fieldName);
    if ($this->isUsedDefaultValidators()) {
      $this->addDefaultValidators($this->element);
    }
    foreach ($this->getValidators() as $validator) {
      if (!($validator instanceof \Zend_Validate_Abstract)) {
        throw new Exception("Validator object must extends \Zend_Validate_Abstract");
      }
      $this->addValidator($this->element, $validator);
    }
    if ($this->getLabel() === null) {
      $this->setLabel($this->namespace . $fieldName);
    }
    if ($this->getPlaceHolder() === null) {
      $this->setPlaceHolder($this->namespace . $fieldName);
    }
    $this->element->setLabel($this->getLabel());
    return $this->element;
  }

  /**
   * @return \Zend_Form_Element
   */
  protected abstract function buildElement($fieldName);

}