<?php
namespace Equ\Entity\FormBuilder;

class ForeignElementIterator extends \FilterIterator {

  private $ignoredFields;

  public function __construct(\Iterator $associationMappings, array $ignoredFields) {
    parent::__construct($associationMappings);
    $this->setIgnoredFields($ignoredFields);
  }

  public function getIgnoredFields() {
    return $this->ignoredFields;
  }

  public function setIgnoredFields(array $ignoredFields) {
    $this->ignoredFields = $ignoredFields;
    return $this;
  }

  public function accept() {
    $fieldName = $this->getInnerIterator()->key();
    $definition = $this->getInnerIterator()->current();
    if (\in_array($fieldName, $this->getIgnoredFields())) {
      return false;
    }
    if (\array_key_exists('isOwningSide', $definition) && !$definition['isOwningSide']) {
      return false;
    }
    return true;
  }

}