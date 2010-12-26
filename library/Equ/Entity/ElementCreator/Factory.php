<?php
namespace Equ\Entity\ElementCreator;

interface Factory {

  /**
   * @return StringCreator
   */
  public function createStringCreator();

  /**
   * @return IntegerCreator
   */
  public function createIntegerCreator();
//
//  public function createSmallintCreator();
//
//  public function createBigintCreator();
//
//  public function createBooleanCreator();
//
//  public function createDecimalCreator();

}