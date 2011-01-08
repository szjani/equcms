<?php
namespace Equ\Form;
use Equ\DTO;

class DTOBuilder implements \Equ\FormVisitor {

  private $dto;

  public function getDTO() {
    return $this->dto;
  }

  /**
   * @param \Zend_Form $form
   */
  public function visitForm(\Zend_Form $form) {
    $this->dto = new DTO();
    foreach ($form as $key => $value) {
      $this->dto->setData($value, $key);
    }
  }


}