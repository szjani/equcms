<?php
namespace Equ\Entity;

interface IVisitable {

  public function accept(IEntityVisitor $visitor);

}