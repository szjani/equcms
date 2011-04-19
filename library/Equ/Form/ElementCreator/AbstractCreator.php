<?php
namespace Equ\Form\ElementCreator;

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

  const PLACEHOLDER          = 0x1;
  const LABEL                = 0x2;
  const IMPLICIT_VALIDATORS  = 0x4;
  const EXPLICIT_VALIDATORS  = 0x8;

  private $flags = 0;

  /**
   * @var array
   */
  protected $values;

  /**
   * @var string
   */
  private $namespace = '';

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
   * @var array
   */
  private $validators = array();

  /**
   * @param int $flags
   * @param string $namespace
   */
  public function __construct($namespace = '', $flags = 14) {
    $this
      ->setNamespace($namespace)
      ->setFlags($flags);
  }

  public function addFlag($const) {
    $this->flags = $this->flags | (int)$const;
    return $this;
  }

  public function removeFlag($const) {
    $this->flags = $this->flags & ~$const;
    return $this;
  }

  public function hasFlag($const) {
    return 0 < ($this->flags & $const);
  }

  public function setFlag($const, $boolean) {
    return $boolean ? $this->addFlag($const) : $this->removeFlag($const);
  }

  public function setFlags($flags) {
    $this->flags = $flags;
    return $this;
  }

  public function setNamespace($namespace) {
    $this->namespace = \rtrim($namespace, '/');
    return $this;
  }

  public function getNamespace() {
    return $this->namespace;
  }

  /**
   * @param \Zend_Validate_Abstract $validator
   * @return AbstractCreator
   */
  public function addValidator(\Zend_Validate_Abstract $validator) {
    $this->validators[] = $validator;
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
   * You should pass $values from
   * $em->getClassMetadata($className)->fieldMappings
   *
   * @param string $fieldName
   * @param array $values field mapping array of entity
   * @return \Zend_Form_Element
   */
  public function createElement($fieldName, array $values = array()) {
    $this->values = $values;
    $element = $this->buildElement($fieldName);
    if ($this->hasFlag(self::IMPLICIT_VALIDATORS)) {
      $this->createImplicitValidators($element);
    }
    if ($this->hasFlag(self::EXPLICIT_VALIDATORS)) {
      $this->createExplicitValidators($element);
    }
    if ($this->hasFlag(self::LABEL)) {
      $this->createLabel($element);
    }
    if ($this->hasFlag(self::PLACEHOLDER)) {
      $this->createPlaceholder($element);
    }
    return $element;
  }

  /**
   * @param \Zend_Form_Element $element
   * @return AbstractCreator
   */
  protected function createImplicitValidators(\Zend_Form_Element $element) {
    foreach ($this->getValidators() as $validator) {
      if (!($validator instanceof \Zend_Validate_Abstract)) {
        throw new Exception("Validator object must extends \Zend_Validate_Abstract");
      }
      $element->addValidator($validator);
      $this->validatorAdded($element, $validator);
    }
    return $this;
  }

  /**
   * @param \Zend_Form_Element $element
   * @return AbstractCreator
   */
  protected function createExplicitValidators(\Zend_Form_Element $element) {
    if (array_key_exists('nullable', $this->values) && !$this->values['nullable']) {
      $element->setRequired();
      $validator = new \Zend_Validate_NotEmpty();
      $element->addValidator($validator);
      $this->validatorAdded($element, $validator);
    }
    if (array_key_exists('length', $this->values) && \is_numeric($this->values['length'])) {
      $validator = new \Zend_Validate_StringLength();
      $validator->setMax($this->values['length']);
      $element->addValidator($validator);
      $this->validatorAdded($element, $validator);
    }
    return $this;
  }

  /**
   * @param \Zend_Form_Element $element
   * @return AbstractCreator
   */
  protected function createLabel(\Zend_Form_Element $element) {
    if ($this->getLabel() === null) {
      $this->setLabel(ltrim($this->getNamespace() . '/' . $element->getName(), '/'));
    }
    $element->setLabel($this->getLabel());
    return $this;
  }

  /**
   * @param \Zend_Form_Element $element
   * @return AbstractCreator
   */
  protected function createPlaceholder(\Zend_Form_Element $element) {
    if ($this->getPlaceHolder() === null) {
      $this->setPlaceHolder($this->getNamespace() . '/' . $element->getName());
    }
  }

  protected function validatorAdded(\Zend_Form_Element $element, \Zend_Validate_Abstract $validator) {}

  /**
   * @return \Zend_Form_Element
   */
  protected abstract function buildElement($fieldName);

}