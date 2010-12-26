<?php
namespace Equ;

abstract class Entity implements Entity\FormBase {

  public function accept(EntityVisitor $visitor) {
    $visitor->visitEntity($this);
  }

  public function getFieldValidators() {}

}