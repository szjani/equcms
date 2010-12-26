<?php
namespace Equ\Entity\ElementCreator;

abstract class StringCreator extends AbstractCreator {

  public final function getType() {
    return 'string';
  }

}