<?php
namespace Equ\Entity\ElementCreator;

abstract class AbstractCreator {

  protected $values;

  protected $namespace = '';
  
  public function __construct($namespace) {
    $this->namespace = $namespace;
  }
  
  protected function addValidator(\Zend_Form_Element $element, \Zend_Validate_Abstract $validator) {
    $element->addValidator($validator);
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
   * @return \Zend_Form_Element
   */
  public function createElement($fieldName, array $values = array(), $addDefaultValidators = true) {
    $this->values = $values;
    $element = $this->buildElement($fieldName);
    if ($addDefaultValidators) {
      $this->addDefaultValidators($element);
    }
    $element->setLabel($this->namespace . $fieldName);
    return $element;
  }

  /**
   * @return \Zend_Form_Element
   */
  protected abstract function buildElement($fieldName);

}