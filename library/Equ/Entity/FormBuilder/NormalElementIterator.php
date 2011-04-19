<?php
namespace Equ\Entity\FormBuilder;

class NormalElementIterator extends \FilterIterator {

  private $ignoredFields;

  public function __construct(\Iterator $fieldMappings, array $ignoredFields) {
    parent::__construct($fieldMappings);
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
    if (\array_key_exists('id', $definition) && $definition['id']) {
      return false;
    }
    return true;
  }

}