<?php
namespace Equ;

interface EntityVisitor {

  public function visitEntity(Entity\Visitable $entity);

}