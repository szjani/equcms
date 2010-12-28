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
 * @package Zym_Session
 * @subpackage Validator
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Session_Validator_Abstract
 */
require_once 'Zend/Session/Validator/Abstract.php';

/**
 * Validate a session using a cookie value
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Session
 * @subpackage Validator
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Session_Validator_Cookie extends Zend_Session_Validator_Abstract
{
    /**
     * Cookie key
     *
     * @var string
     */
    protected $_key;
    
    /**
     * Cookie value
     *
     * @var string
     */
    protected $_value;
    
    /**
     * Constructor
     * 
     * Validates a session using a cookie with a specific value
     * 
     * Note: All function args are null because Zend_Session instantiates
     *       this validator without constructor args.
     *
     * @param string  $key       Required even though it is null
     * @param string  $value     Cookie value
     * @param boolean $setCookie Wether to set the cookie if it doesn't exist
     */
    public function __construct($key = null, $value = null)
    {
        $this->_key   = (string) $key;
        $this->_value = (string) $value;
    }
    
    /**
     * Setup the validator
     *
     * Implement setup()
     */
    public function setup()
    {
        $this->setValidData(array('key'   => $this->_key, 
                                  'value' => $this->_value));
    }
    
    /**
     * Validation process
     *
     * Implement validate()
     * 
     * @return boolean
     */
    public function validate()
    {
        $data  = $this->getValidData();
        $key   = $data['key'];
        $value = $data['value'];
        
        if (isset($_COOKIE[$key]) && $_COOKIE[$key] == $value) {
            return true;
        }
        
        return false;
    }
}