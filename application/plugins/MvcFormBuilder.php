<?php
namespace plugins;
use Equ\Entity\FormBuilder;

class MvcFormBuilder extends FormBuilder {

  public function getIgnoredFields() {
    return array('lft', 'rgt', 'lvl', 'resource');
  }

}