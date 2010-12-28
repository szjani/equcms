<?php
namespace Equ\Entity;

interface Visitable {

  public function accept(\Equ\EntityVisitor $visitor);

}