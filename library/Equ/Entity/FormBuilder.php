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

  const ELEMENT_PREFIX = 'f_';

  /**
   * @var \Zend_Form
   */
  private $form;

  /**
   * @var EntityManager
   */
  protected $entityManager;

  /**
   * @var \Equ\Entity\FormBase
   */
  protected $entity;

  /**
   * @var ElementCreator\Factory
   */
  protected $elementCreatorFactory = null;

  protected $fieldElementCreators = array();

  protected $createDefaultValidators = true;

  protected $disabledForeignElements = false;

  protected $ignoredFields = array();

  protected $fieldLabels = array();

  public function isCreateDefaultValidators() {
    return $this->createDefaultValidators;
  }

  public function createDefaultValidators($bool = true) {
    $this->createDefaultValidators = $bool;
    return $this;
  }

  public function createElementName($fieldName) {
    return self::ELEMENT_PREFIX . $fieldName;
  }

  public function disableForeignElements($disable = true) {
    $this->disabledForeignElements = (boolean)$disable;
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

  protected function getEntityClassMetadata() {
    return $this->entityManager->getClassMetadata(\get_class($this->entity));
  }

  public function visitEntity(\Equ\Entity\FormBase $entity) {
    $this->entity = $entity;
    $this->getElementCreatorFactory()->setNamespace(
      str_replace(array('\\', '_'), '/', $this->getEntityClassMetadata()->name) . '/'
    );
    $this->preVisit();
    $this->createElements();
    $this->postVisit();
  }

  protected function createNormalElements() {
    $metadata = $this->getEntityClassMetadata();
    foreach ($metadata->fieldMappings as $fieldName => $def) {
      $elementName = $this->createElementName($fieldName);
      if ($this->isIgnoredField($fieldName)) {
        continue;
      }
      if (\array_key_exists('id', $def) && $def['id']) {
        continue;
      }
      $elementCreator = $this->getElementCreator($def)
        ->useDefaultValidators($this->isCreateDefaultValidators())
        ->setValidators($this->entity->getFieldValidators($fieldName));
      $labels  = $this->getFieldLabels();
      if (\array_key_exists($fieldName, $labels)) {
        $elementCreator->setLabel($labels[$fieldName]);
        $elementCreator->setPlaceHolder($labels[$fieldName]);
      }
      $element = $elementCreator->createElement($elementName, $def);
      $this->form->addElement($element);
      $this->form->setDefault($elementName, $metadata->getFieldValue($this->entity, $fieldName));
    }
  }

  /**
   * 
   */
  protected function createForeignElements() {
    $metadata = $this->getEntityClassMetadata();
    foreach ($metadata->associationMappings as $fieldName => $def) {
      $elementName = $this->createElementName($fieldName);
      if ($this->isIgnoredField($fieldName) || !$def['isOwningSide']) {
        continue;
      }
      $select = $this->getElementCreatorFactory()->createArrayCreator()->createElement($elementName);
      $select->addMultiOption('0', '');
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
        $this->form->setDefault($elementName, $targetMetaData->getFieldValue($value, $targetMetaData->getSingleIdentifierFieldName()));
      }
    }
  }

  /**
   * 
   */
  protected function createElements() {
    $this->createNormalElements();
    if (!$this->disabledForeignElements) {
      $this->createForeignElements();
    }
    if (!($this->form instanceof \Zend_Form_SubForm)) {
      $save = $this->getElementCreatorFactory()->createSubmitCreator()->createElement('save');
      $save->setOrder(100);
      $this->form->addElement($save);
    }
  }

}