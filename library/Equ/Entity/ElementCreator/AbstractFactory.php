<?php
namespace Equ\Entity\ElementCreator;

abstract class AbstractFactory implements Factory {
  
  private $namespace = null;
  
  public function setNamespace($ns) {
    $this->namespace = $ns;
    return $this;
  }
  
  public function getNamespace() {
    return $this->namespace;
  }
  
}