<?php
namespace Equ\Entity;
use Equ\Entity\ElementCreator;
use Doctrine\ORM\EntityManager;

/**
 * Create form from entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class FormBuilder implements \Equ\EntityVisitor {

  /**
   * @var \Zend_Form
   */
  private $form;

  /**
   * @var EntityManager
   */
  protected $entityManager;

  /**
   * @var \Equ\Entity\Visitable
   */
  protected $entity;

  /**
   * @var ElementCreator\Factory
   */
  protected $elementCreatorFactory = null;

  protected $fieldElementCreators = array();

  protected $createDefaultValidators = true;

  protected $ignoredFields = array();

  protected $fieldLabels = array();

  public function isCreateDefaultValidators() {
    return $this->createDefaultValidators;
  }

  public function createDefaultValidators($bool = true) {
    $this->createDefaultValidators = $bool;
    return $this;
  }

  /**
   * @param string $fieldName
   * @param ElementCreator\AbstractCreator $creator
   * @return FormBuilder
   */
  public function setFieldElementCreator($fieldName, ElementCreator\AbstractCreator $creator) {
    $this->fieldElementCreators[$fieldName] = $creator;
    return $this;
  }

  /**
   *
   * @return ElementCreator\AbstractCreator
   */
  public function getElementCreator(array $def) {
    $type      = $def['type'];
    $fieldName = $def['fieldName'];
    $creator   = null;
    if (\array_key_exists($fieldName, $this->fieldElementCreators)) {
      $creator = $this->fieldElementCreators[$fieldName];
    } else {
      $factory = $this->getElementCreatorFactory();
      $method = 'create' . \ucfirst($type) . 'Creator';
      if (\method_exists($factory, $method)) {
        $creator = $factory->$method();
      } else {
        $creator = $factory->createStringCreator();
      }
    }
    return $creator;
  }

  /**
   * @param ElementCreator\Factory $factory
   * @return FormBuilder
   */
  public function setElementCreatorFactory(ElementCreator\Factory $factory) {
    $this->elementCreatorFactory = $factory;
    return $this;
  }

  /**
   * @return ElementCreator\Factory
   */
  public function getElementCreatorFactory() {
    if ($this->elementCreatorFactory === null) {
      $this->elementCreatorFactory = new ElementCreator\Builtin\Factory();
    }
    $this->elementCreatorFactory->setNamespace(str_replace(array('\\', '_'), '/', get_class($this->entity)) . '/');
    return $this->elementCreatorFactory;
  }

  public function getIgnoredFields() {
    return $this->ignoredFields;
  }

  protected function getFieldLabels() {
    return $this->fieldLabels;
  }

  protected function preVisit() {}
  protected function postVisit() {}

  /**
   * @param EntityManager $em
   * @param \Zend_Form $form
   */
  public function __construct(EntityManager $em, $createDefaultValidators = true) {
    $this->entityManager = $em;
    $this->createDefaultValidators = $createDefaultValidators;
  }

  /**
   * @return \Equ\Form
   */
  public function getForm() {
    if ($this->form === null) {
      $this->form = new \Equ\Form();
    }
    return $this->form;
  }

  public function setForm(\Zend_Form $form) {
    $this->form = $form;
    return $this;
  }

  public function isIgnoredField($field) {
    return \in_array($field, $this->getIgnoredFields());
  }

  protected function getMetadata() {
    return $this->entityManager->getClassMetadata(\get_class($this->entity));
  }

  public function visitEntity(\Equ\Entity\Visitable $entity) {
    $this->entity = $entity;
    $this->preVisit();
    $this->createElements();
    $this->postVisit();
  }

  protected function createNormalElements() {
    $metadata = $this->getMetadata();
    foreach ($metadata->fieldMappings as $fieldName => $def) {
      if ($this->isIgnoredField($fieldName)) {
        continue;
      }
      if (\array_key_exists('id', $def) && $def['id']) {
        continue;
      }
      $element = $this->getElementCreator($def)->createElement($fieldName, $def, $this->isCreateDefaultValidators());
      $labels  = $this->getFieldLabels();
      if (\array_key_exists($fieldName, $labels)) {
        $element->setLabel($labels[$fieldName]);
      }
      $this->form->addElement($element);
      $this->form->setDefault($fieldName, $metadata->getFieldValue($this->entity, $fieldName));
    }
  }

  protected function createForeignElements() {
    $metadata = $this->getMetadata();
    foreach ($metadata->associationMappings as $fieldName => $def) {
      if ($this->isIgnoredField($fieldName) || !$def['isOwningSide']) {
        continue;
      }
      $select = $this->getElementCreatorFactory()->createArrayCreator()->createElement($fieldName);
      $select->addMultiOption('0', '---');
      $targetMetaData = $this->entityManager->getClassMetadata($def['targetEntity']);
      foreach ($this->entityManager->getRepository($def['targetEntity'])->findAll() as $entity) {
        $select->addMultiOption(
          $targetMetaData->getFieldValue(
            $entity,
            $targetMetaData->getSingleIdentifierFieldName()),
          (string)$entity
        );
      }
      $this->form->addElement($select);
      
      $value = $metadata->getFieldValue($this->entity, $fieldName);
      if ($value instanceof $def['targetEntity']) {
        $targetMetaData = $this->entityManager->getClassMetadata(\get_class($value));
        $this->form->setDefault($fieldName, $targetMetaData->getFieldValue($value, $targetMetaData->getSingleIdentifierFieldName()));
      }
    }
  }

  protected function createElements() {
    $this->createNormalElements();
    $this->createForeignElements();
    if (!($this->form instanceof \Zend_Form_SubForm)) {
      $save = $this->getElementCreatorFactory()->createSubmitCreator()->createElement('save');
      $this->form->addElement($save);
    }
  }

}