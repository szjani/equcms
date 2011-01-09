<?php
namespace modules\group\plugins;
use Equ\Entity\FormBuilder;

class CrudFormBuilder extends FormBuilder {

  public function getIgnoredFields() {
    return array('lft', 'rgt', 'lvl', 'role');
  }

}