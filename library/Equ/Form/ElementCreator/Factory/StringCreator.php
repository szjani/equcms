<?php
namespace Equ\Form\ElementCreator\Factory;

class StringCreator extends \Equ\Form\ElementCreator\AbstractCreator {

  protected function buildElement($fieldName) {
    $element = new \Zend_Form_Element_Text($fieldName);
    $element->getView()->addBasePath(__DIR__ . '/view');
    $element->addDecorators(array(
      array('ViewScript', array(
        'placement' => false,
        'viewScript' => 'default.phtml',
      ))
    ));
    return $element;
  }

}