<?php
use Equ\Entity\FormBuilder;

class Default_Plugin_MvcFormBuilder extends FormBuilder {

  public function  getIgnoredFields() {
    return array('lft', 'rgt', 'lvl', 'url');
  }

}