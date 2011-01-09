<?php
namespace Equ\DTO;
use Equ\DTOVisitor;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class FormFiller implements DTOVisitor {

  /**
   * @var \Zend_Form
   */
  private $form;

  /**
   * @param \Zend_Form $form
   */
  public function __construct(\Zend_Form $form) {
    $this->form = $form;
  }

  /**
   * @param \Equ\DTO $dto
   */
  public function visitDTO(\Equ\DTO $dto) {
    foreach ($dto->getIterator() as $key => $value) {
      $this->form->setDefault($key, $value);
    }
  }


}