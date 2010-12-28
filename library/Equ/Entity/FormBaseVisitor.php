<?php
namespace Equ\Entity;

interface FormBaseVisitor extends \Equ\EntityVisitor {

  public function visitEntity(FormBase $entity);

}