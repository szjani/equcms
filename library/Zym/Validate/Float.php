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
 * @see Zend_Locale_Format
 */
require_once 'Zend/Locale/Format.php';

/**
 * A Zend_Locale aware float validator
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Validate
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Validate_Float extends Zend_Validate_Abstract
{
    /**
     * Not float key
     *
     */
    const NOT_FLOAT = 'notFloat';
    
    /**
     * Zend_Locale_Format options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Error messages
     * 
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_FLOAT => '"%value%" does not appear to be a float'
    );

    /**
     * Construct
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->_options = $options;
    }
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a floating-point value
     * 
     * @todo http://framework.zend.com/issues/browse/ZF-2895
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;

        $this->_setValue($valueString);

        if (!Zend_Locale_Format::isFloat($valueString, $this->_options)) {
            $this->_error();
            return false;
        }

        return true;
    }
}