<?php
namespace Equ\Entity\ElementCreator;

abstract class IntegerCreator extends AbstractCreator {

  public final function getType() {
    return 'integer';
  }

}