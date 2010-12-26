<?php
namespace Equ\Form;

interface Visitable {

  public function accept(\Equ\FormVisitor $visitor);

}