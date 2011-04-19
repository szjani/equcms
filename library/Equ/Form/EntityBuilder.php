<?php
namespace Equ\Form;
use Equ\AbstractEntityBuilder;

/**
 * Create entity from form
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class EntityBuilder extends AbstractEntityBuilder implements IFormVisitor {

  /**
   * @var \Zend_Form
   */
  protected $form;

  /**
   * @param \Zend_Form $form
   */
  public function visitForm(\Zend_Form $form) {
    $this->form = $form;
    $this->preVisit();
    $values = $form->getValues();
    $metadata = $this->entityManager->getClassMetadata(\get_class($this->getEntity()));

    foreach ($values as $name => $value) {
      $setterMethod = 'set' . \ucfirst($name);
      if (\array_key_exists($name, $metadata->fieldMappings) && \method_exists($this->getEntity(), $setterMethod)) {
        $this->getEntity()->$setterMethod($value);
      } elseif (\array_key_exists($name, $metadata->associationMappings)) {
        if (\method_exists($this->getEntity(), $setterMethod)) {
          $targetEntity = null;
          if ('0' != $value) {
            $relatedClass = $metadata->associationMappings[$name]['targetEntity'];
            $targetEntity = $this->entityManager->getReference($relatedClass, $value);
            if (!isset($targetEntity)) {
              throw new \Equ\Exception("Invalid '$relatedClass' id: $value");
            }
          }
          $this->getEntity()->$setterMethod($targetEntity);
        }
      }
    }
    $this->postVisit();
  }

}