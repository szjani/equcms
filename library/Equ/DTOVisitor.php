<?php
namespace Equ;
use DTO;

interface DTOVisitor {

  public function visitDTO(DTO $dto);

}