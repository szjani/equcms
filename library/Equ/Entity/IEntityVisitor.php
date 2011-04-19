<?php
namespace Equ\Entity;

interface IEntityVisitor {

  public function visitEntity(IFormBase $entity);

}