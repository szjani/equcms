<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category Zym
 * @package Zym_Validate
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * Validates whether all strings in an array are equal
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Validate
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Validate_StringEquals extends Zend_Validate_Abstract
{
    /**
     * Validation key for not equal
     *
     */
    const NOT_EQUAL = 'notEqual';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EQUAL => 'Not all strings are equal',
    );

    /**
     * Construct
     *
     */
    public function __construct()
    {}

    /**
     * Validate an array of values
     *
     * @param array|string $value
     * @return boolean
     */
    public function isValid($value)
    {
        // Set values
        $this->_setValue((array) $value);

        // Check if equals
        $referenceValue = array_shift($this->_value);
        foreach ($this->_value as $val) {
               if ($val != $referenceValue) {
                   $this->_error(self::NOT_EQUAL);
                   return false;
               }
        }

        return true;
    }
}