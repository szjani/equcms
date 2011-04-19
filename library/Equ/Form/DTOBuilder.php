<?php
namespace Equ\Form;
use
  Equ\DTO,
  Equ\Entity\FormBuilder;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class DTOBuilder implements IFormVisitor {

  /**
   * @var DTO
   */
  private $dto;

  /**
   * @var string
   */
  private $prefix = FormBuilder::ELEMENT_PREFIX;

  /**
   * If you use prefixes in form elements' name,
   * you can remove them before create DTO object.
   *
   * @param string $prefix
   */
  public function setRemovablePrefix($prefix) {
    $this->prefix = (string)$prefix;
  }

  /**
   * @return DTO
   */
  public function getDTO() {
    return $this->dto;
  }

  /**
   * @param \Zend_Form $form
   */
  public function visitForm(\Zend_Form $form) {
    $this->dto = new DTO();
    /* @var $element \Zend_Form_Element */
    foreach ($form as $key => $element) {
      $realKey = $key;
      if ($this->prefix !== null && \strpos($key, $this->prefix) === 0) {
        $realKey = \substr($key, \strlen($this->prefix));
      }
      $this->dto->setData($element->getValue(), $realKey);
    }
  }
}