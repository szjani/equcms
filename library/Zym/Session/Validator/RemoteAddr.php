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
 * Validate a session using a user's IP address
 * 
 * @todo expand this component to handle HTTP_X_FORWARDED_FOR etc...
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Session
 * @subpackage Validator
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Session_Validator_RemoteAddr extends Zend_Session_Validator_Abstract
{
    /**
     * Setup the validator
     *
     * Implement setup()
     */
    public function setup()
    {
        $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $this->setValidData($remoteAddr);
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
        $remoteAddr     = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $lastRemoteAddr = $this->getValidData();

        if ($remoteAddr == $lastRemoteAddr) {
            return true;
        }
        
        return false;
    }
}