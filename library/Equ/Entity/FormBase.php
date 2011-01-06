<?php
namespace Equ\Entity;

/**
 * To use CRUD you have to implement this interface in your entity class
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
interface FormBase extends Visitable {

  /**
   * @paramt string $fieldName
   * @return \Zend_Validate_Abstract[]
   */
  public function getFieldValidators($fieldName);

  /**
   * @param string $fieldName
   * @return FormBase $this
   */
  public function clearFieldValidators($fieldName);

  /**
   * @param string $fieldName
   * @param \Zend_Validate_Abstract $validator
   * @return FormBase $this
   */
  public function addFieldValidator($fieldName, \Zend_Validate_Abstract $validator);

  /**
   * Called from __construct() automatically
   */
  public function init();

  /**
   * @return int
   */
  public function getId();

}