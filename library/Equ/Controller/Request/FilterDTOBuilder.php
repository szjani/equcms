<?php
namespace Equ\Controller\Request;
use
  Equ\DTO,
  Equ\Entity\FormBuilder;

class FilterDTOBuilder implements IDTOVisitor {

  private $dto;

  public function getDTO() {
    return $this->dto;
  }

  public function visitRequest(\Zend_Controller_Request_Abstract $request) {
    $this->dto = new DTO();
    $prefixLength = strlen(FormBuilder::ELEMENT_PREFIX);
    foreach ($request->getParams() as $key => $value) {
      if (\substr($key, 0, $prefixLength) == FormBuilder::ELEMENT_PREFIX) {
        $this->dto->setData($value, \substr($key, $prefixLength));
      }
    }
  }

}