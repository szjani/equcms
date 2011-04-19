<?php
namespace Equ\Entity;

interface IFormBaseVisitor extends IEntityVisitor {

  public function visitEntity(IFormBase $entity);

}