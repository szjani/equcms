<?php
namespace Equ\Entity\ElementCreator\Builtin;

class Factory implements \Equ\Entity\ElementCreator\Factory {

  public function createStringCreator() {
    return new StringCreator();
  }

  public function createIntegerCreator() {
    return new IntegerCreator();
  }
//
//  public function createSmallintCreator();
//
//  public function createBigintCreator();
//
//  public function createBooleanCreator();
//
//  public function createDecimalCreator();

}