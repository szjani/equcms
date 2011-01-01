<?php
namespace Equ\Entity\ElementCreator;

interface Factory {

  /**
   * @param string $ns
   * @return Factory $this
   */
  public function setNamespace($ns);
  
  /**
   * @return AbstractCreator
   */
  public function createStringCreator();

  /**
   * @return AbstractCreator
   */
  public function createIntegerCreator();
  
  /**
   * @return AbstractCreator
   */
  public function createSmallintCreator();

  /**
   * @return AbstractCreator
   */
  public function createBigintCreator();

  /**
   * @return AbstractCreator
   */
  public function createDecimalCreator();

  /**
   * @return AbstractCreator
   */
  public function createFloatCreator();
  
  /**
   * @return AbstractCreator
   */
  public function createBooleanCreator();
  
  /**
   * @return AbstractCreator
   */
  public function createDateCreator();

  /**
   * @return AbstractCreator
   */
  public function createTimeCreator();

  /**
   * @return AbstractCreator
   */
  public function createDateTimeCreator();
  
  /**
   * @return AbstractCreator
   */
  public function createTextCreator();

  /**
   * @return AbstractCreator
   */
  public function createObjectCreator();

  /**
   * @return AbstractCreator
   */
  public function createArrayCreator();
  
  /**
   * @return AbstractCreator
   */
  public function createSubmitCreator();

}