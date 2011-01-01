<?php
namespace plugins;
use Equ\Entity\FormBuilder;

//class Index_Plugin_MvcFormBuilder extends FormBuilder {
class MvcFormBuilder extends FormBuilder {

  public function  getIgnoredFields() {
    return array('lft', 'rgt', 'lvl', 'url');
  }

}