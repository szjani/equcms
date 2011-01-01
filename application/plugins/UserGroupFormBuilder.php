<?php
namespace plugins;
use Equ\Entity\FormBuilder;

class UserGroupFormBuilder extends FormBuilder {

  public function getIgnoredFields() {
    return array('lft', 'rgt', 'lvl');
  }

}