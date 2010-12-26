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
 * @package Zym_Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @see Zend_Locale_Format
 */
require_once 'Zend/Locale/Format.php';

/**
 * Converts values to floats
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Filter_Float implements Zend_Filter_Interface
{
    /**
     * Zend Locale Format options
     *
     * @var array
     */
    protected $_options = array();
    
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
     * Defined by Zend_Filter_Interface
     *
     * Returns (float) $value
     *
     * @param  string $value
     * @return float
     */
    public function filter($value)
    {
        return (float) Zend_Locale_Format::getFloat($value, $this->_options);
    }
}