<?php
namespace Equ\Form;

interface IVisitable {

  public function accept(IFormVisitor $visitor);

}