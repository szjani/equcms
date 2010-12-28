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
 * Validates all fields are equal
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Validate
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Validate_Confirm extends Zend_Validate_Abstract
{
    /**
     * Validation key for not equal
     *
     */
    const NOT_SAME = 'notSame';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_SAME => 'Values are not the same',
    );

    /**
     * Field to validate with
     *
     * @var string
     */
    protected $_field;

    /**
     * Context
     *
     * @var string|array
     */
    protected $_context;

    /**
     * Construct
     *
     */
    public function __construct($field, $context = null)
    {
        $this->_field   = $field;
        $this->_context = $context;
    }

    /**
     * Validate to a context
     *
     * @param string $value
     * @param array|string $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        // Set value
        $this->_setValue($value);

        if ($context === null && $this->_context === null) {
            /**
             * @see Zym_Validate_Exception
             */
            require_once 'Zym/Validate/Exception.php';
            throw new Zym_Validate_Exception(sprintf(
                'Validator "%s" contexts is not setup', get_class($this)
            ));
        }

        // Use instance context if not provided
        $context = ($context === null) ? $this->_context : $context;

        // Validate string
        if (is_string($context) && $value == $context) {
             return true;
        }

        // Validate from array
        if (is_array($context) && isset($context[$this->_field])
            && $value == $context[$this->_field]) {
            return true;
        }

        $this->_error(self::NOT_SAME);
        return false;
    }
}