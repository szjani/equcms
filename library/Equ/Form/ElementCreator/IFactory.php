<?php
namespace Equ\Form\ElementCreator;

/**
 * FormBuilder objects use an implementation
 * of this interface to create form elements.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
interface IFactory {

  /**
   * @param string $ns
   * @return Factory
   */
  public function setNamespace($ns);

  /**
   * @param boolean $use
   * @return Factory
   */
  public function usePlaceHolders($use = true);
  
  /**
   * @return boolean
   */
  public function isUsedPlaceHolders();

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