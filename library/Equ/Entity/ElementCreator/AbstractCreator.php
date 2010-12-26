<?php
namespace Equ\Entity\ElementCreator;

abstract class AbstractCreator {

  protected $values;

  public abstract function getType();

  /**
   * @return array
   */
  protected function createDefaultValidators() {
    $validators = array();
    if (!$this->values['nullable']) {
      $validators[] = new \Zend_Validate_NotEmpty();
    }
    if (\is_numeric($this->values['length'])) {
      $validator = new \Zend_Validate_StringLength();
      $validator->setMax($this->values['length']);
      $validators[] = $validator;
    }
    return $validators;
  }

  /**
   * @return \Zend_Form_Element
   */
  public function createElement($fieldName, array $values = array(), $addDefaultValidators = true) {
    $this->values = $values;
    $element = $this->buildElement($fieldName);
    if ($addDefaultValidators) {
      if (!$this->values['nullable']) {
        $element->setRequired();
      }
      $element->addValidators($this->createDefaultValidators());
    }
    return $element;
  }

  /**
   * @return \Zend_Form_Element
   */
  protected abstract function buildElement($fieldName);

}