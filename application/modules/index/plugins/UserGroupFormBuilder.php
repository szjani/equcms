<?php
use Equ\Entity\FormBuilder;

class Index_Plugin_UserGroupFormBuilder extends FormBuilder {

  public function getIgnoredFields() {
    return array('lft', 'rgt', 'lvl');
  }

}