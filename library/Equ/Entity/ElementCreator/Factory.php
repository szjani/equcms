<?php
namespace Equ\Entity\ElementCreator;

interface Factory {

  /**
   * @param string $ns
   * @return Factory $this
   */
  public function setNamespace($ns);
  
  /**
   * @return \Zend_Form_Element
   */
  public function createStringCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createIntegerCreator();
  
  /**
   * @return \Zend_Form_Element
   */
  public function createSmallintCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createBigintCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createDecimalCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createFloatCreator();
  
  /**
   * @return \Zend_Form_Element
   */
  public function createBooleanCreator();
  
  /**
   * @return \Zend_Form_Element
   */
  public function createDateCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createTimeCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createDateTimeCreator();
  
  /**
   * @return \Zend_Form_Element
   */
  public function createTextCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createObjectCreator();

  /**
   * @return \Zend_Form_Element
   */
  public function createArrayCreator();
  
  /**
   * @return \Zend_Form_Element
   */
  public function createSubmitCreator();

}