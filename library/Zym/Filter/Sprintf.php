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
 * Filter using sprintf
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Filter_Sprintf implements Zend_Filter_Interface
{
    /**
     * Sprintf args
     *
     * @var array
     */
    protected $_args = array();

    /**
     * Construct
     *
     * @param [, mixed $args [, mixed $...]]
     */
    public function __construct()
    {
        if (func_num_args()) {
            call_user_func_array(array($this, 'setArgs'), func_get_args());
        }
    }

    /**
     * Set args
     *
     * @param [, mixed $args [, mixed $...]]
     * @return Zym_Filter_Sprintf
     */
    public function setArgs()
    {
        $this->_args = func_get_args();
        return $this;
    }

    /**
     * Get args
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->_args;
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  string $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return string
     */
    public function filter($value)
    {
        return call_user_func_array('sprintf', array_merge(array((string) $value), $this->getArgs()));
    }
}