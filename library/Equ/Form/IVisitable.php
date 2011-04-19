<?php
namespace Equ\Form;

interface IVisitable {

  public function accept(\Equ\IFormVisitor $visitor);

}