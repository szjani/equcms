<?php
namespace Equ\Form\ElementCreator\Factory;

class Factory extends \Equ\Form\ElementCreator\AbstractFactory {
  
  public function createStringCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createIntegerCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createSmallintCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createBigintCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createBooleanCreator() {
    return new CheckboxCreator($this->getNamespace());
  }
  
  public function createDecimalCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createArrayCreator() {
    return new ArrayCreator($this->getNamespace());
  }
  
  public function createDateCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createDateTimeCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createFloatCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createObjectCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createTextCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createTimeCreator() {
    return new StringCreator($this->getNamespace());
  }
  
  public function createSubmitCreator() {
    return new SubmitCreator($this->getNamespace());
  }
}