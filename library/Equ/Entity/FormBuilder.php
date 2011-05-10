<?php
namespace Equ\Entity;
use
  Equ\Form\ElementCreator,
  Doctrine\ORM\EntityManager,
  Equ\Entity\FormBuilder\NormalElementIterator,
  Equ\Entity\FormBuilder\ForeignElementIterator,
  Equ\Entity\IFormBase;

/**
 * Create form from entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class FormBuilder implements IEntityVisitor {

  const ELEMENT_PREFIX = 'f_';

  /**
   * @var \Zend_Form
   */
  private $form;

  private $metaData = null;

  /**
   * @var ElementCreator\IFactory
   */
  private $elementCreatorFactory = null;

  private $fieldElementCreators = array();

  private $disableDefaultValidators = false;

  private $disabledForeignElements = false;

  protected $ignoredFields = array();

  protected $fieldLabels = array();
  
  /**
   * @var EntityManager
   */
  protected $entityManager;

  /**
   * @var \Equ\Entity\IFormBase
   */
  protected $entity;
  
  /**
   * @param EntityManager $em
   * @param \Zend_Form $form
   */
  public function __construct(EntityManager $em, $disableDefaultValidators = false) {
    $this->entityManager = $em;
    $this->disableDefaultValidators = $disableDefaultValidators;
  }
  
  /**
   * @return EntityManager $em
   */
  public function getEntityManager() {
    return $this->entityManager;
  }

  /**
   * @see disableExplicitValidators()
   * @return boolean
   */
  public function isDisabledDefaultValidators() {
    return $this->disableDefaultValidators;
  }

  /**
   * Entity's fields can impact some explicit validators like
   *  - StringLenght
   *  - Nullable
   * 
   * You can disable it.
   *
   * @param boolean $bool
   * @return FormBuilder 
   */
  public function disableExplicitValidators($bool = true) {
    $this->disableDefaultValidators = $bool;
    return $this;
  }

  /**
   * Retrieves the prefixed name of field for form element's name attribute.
   * It is usefull to use fields like 'module', 'controller', 'action'
   * because without the prefix the URL parameters will be overwritten.
   * 
   * @param string $fieldName
   * @return string
   */
  public function createElementName($fieldName) {
    return self::ELEMENT_PREFIX . $fieldName;
  }

  /**
   * You can disable foreign element generation
   * 
   * @param boolean $disable
   * @return FormBuilder 
   */
  public function disableForeignElements($disable = true) {
    $this->disabledForeignElements = (boolean)$disable;
    return $this;
  }

  /**
   * You can specify which element creator you want to use for the given field
   * 
   * @param string $fieldName
   * @param ElementCreator\AbstractCreator $creator
   * @return FormBuilder
   */
  public function setFieldElementCreator($fieldName, ElementCreator\AbstractCreator $creator) {
    $this->fieldElementCreators[$fieldName] = $creator;
    return $this;
  }

  /**
   * It try to retrieve the correct element creator
   * depending on the field type.
   * If not success, stringCreator will be used.
   * You can set implicit creators to fields.
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
   * @param ElementCreator\IFactory $factory
   * @return FormBuilder
   */
  public function setElementCreatorFactory(ElementCreator\IFactory $factory) {
    $this->elementCreatorFactory = $factory;
    return $this;
  }

  /**
   * @return ElementCreator\IFactory
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
  
  public function setIgnoredFields(array $fields) {
    $this->ignoredFields = $fields;
    return $this;
  }

  protected function getFieldLabels() {
    return $this->fieldLabels;
  }

  /**
   * Retrieves the output form. If you call it before visit()
   * the form will empty.
   * Basically the form object will be an \Equ\Form instance
   * but you can overwrite this functionality in the extended class.
   * 
   * @return \Equ\Form
   */
  public function getForm() {
    if ($this->form === null) {
      $this->form = new \Equ\Form();
    }
    return $this->form;
  }

  /**
   * @param \Zend_Form $form
   * @return FormBuilder
   */
  public function setForm(\Zend_Form $form) {
    $this->form = $form;
    return $this;
  }

  /**
   * Checks that a field is ignored or not
   * 
   * @param string $field
   * @return boolean
   */
  public function isIgnoredField($field) {
    return \in_array($field, $this->getIgnoredFields());
  }

  protected function getEntityClassMetadata() {
    if ($this->metaData === null) {
      $this->metaData = $this->entityManager->getClassMetadata(\get_class($this->entity));
    }
    return $this->metaData;
  }
  
  /**
   * FilterIterator that retrieves fields to generate form elements
   * to field in the entity
   *
   * @return NormalElementIterator
   */
  public function getNormalElementIterator() {
    $fieldMapping = new \ArrayObject($this->getEntityClassMetadata()->fieldMappings);
    return new NormalElementIterator($fieldMapping->getIterator(), $this->getIgnoredFields());
  }

  /**
   * FilterIterator that retrieves fields to generate form elements
   * to foreign fields
   *
   * @return NormalElementIterator
   */
  public function getForeignElementIterator() {
    $associationMapping = new \ArrayObject($this->getEntityClassMetadata()->associationMappings);
    return new ForeignElementIterator($associationMapping->getIterator(), $this->getIgnoredFields());
  }
  
  /**
   * Hook method, it will be called before visit() method.
   * You can modify this object before form creation.
   */
  protected function preVisit() {}
  
  /**
   * Hook method, it will be called after visit() method.
   * You can modify this object after form creation.
   */
  protected function postVisit() {}

  /**
   * You can build form from entity by this method
   * 
   * @param IFormBase $entity
   */
  public function visitEntity(IFormBase $entity) {
    $this->entity = $entity;
    $this->getElementCreatorFactory()->setNamespace(
      str_replace(array('\\', '_'), '/', $this->getEntityClassMetadata()->name)
    );
    $this->preVisit();
    $this->createElements();
    $this->postVisit();
  }
  
  /**
   * Create every elements and a submit button
   * if form object is not a subform.
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

  /**
   * Create form elements with iterator
   * 
   * @return FormBuilder
   */
  protected function createNormalElements() {
    $metadata = $this->getEntityClassMetadata();
    foreach ($this->getNormalElementIterator() as $fieldName => $def) {
      $elementName = $this->createElementName($fieldName);
      $elementCreator = $this->getElementCreator($def)
        ->setFlag(ElementCreator\AbstractCreator::EXPLICIT_VALIDATORS, !$this->isDisabledDefaultValidators())
        ->setValidators($this->entity->getFieldValidators($fieldName));
      $labels = $this->getFieldLabels();
      if (\array_key_exists($fieldName, $labels)) {
        $elementCreator->setLabel($labels[$fieldName]);
        $elementCreator->setPlaceHolder($labels[$fieldName]);
      }
      $element = $elementCreator->createElement($elementName, $def);
      $this->form->addElement($element);
      $this->form->setDefault($elementName, $metadata->getFieldValue($this->entity, $fieldName));
    }
    return $this;
  }

  /**
   * Create elements to foreign fields.
   * Default it try to cast related objects to string,
   * so you should override __toString method in entities.
   *
   * @return FormBuilder
   */
  protected function createForeignElements() {
    foreach ($this->getForeignElementIterator() as $fieldName => $def) {
      $elementName = $this->createElementName($fieldName);
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
      
      $value = $this->getEntityClassMetadata()->getFieldValue($this->entity, $fieldName);
      if ($value instanceof $def['targetEntity']) {
        $this->form->setDefault(
          $elementName,
          $targetMetaData->getFieldValue($value, $targetMetaData->getSingleIdentifierFieldName())
        );
      }
    }
    return $this;
  }

}