<?php
namespace Equ;

interface Validatable {

  /**
   * @param ClassMetadata $metadata
   */
  public static function loadValidatorMetadata(ClassMetadata $metadata);

}