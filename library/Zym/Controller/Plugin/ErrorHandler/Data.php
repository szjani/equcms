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
 * @package Zym_Controller
 * @subpackage Plugin_ErrorHandler
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * Error handler data container
 * 
 * This is used when passed as an error param to the request object
 * 
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Controller
 * @subpackage Plugin_ErrorHandler
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Controller_Plugin_ErrorHandler_Data
{
    /**
     * Exception
     *
     * @var Exception
     */
    protected $_exception;
    
    /**
     * Request that cause the error
     *
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request;
    
    /**
     * Exception type
     *
     * @var string
     */
    protected $_type;
    
    /**
     * Construct
     *
     * @param string $type
     * @param Exception $exception
     * @param Zend_Controller_Request_Abstract $request
     */
    public function __construct($type, Exception $exception, Zend_Controller_Request_Abstract $request)
    {
        $this->_type      = $type;
        $this->_exception = $exception;
        $this->_request   = $request;
    }
    
    /**
     * Get exception
     *
     * @return Exception
     */
    public function getException()
    {
        return $this->_exception;
    }
    
    /**
     * Get request object which caused error
     *
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->_request;
    }
    
    /**
     * Get request type defined by errorHandler
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }
    
    /**
     * Get
     *
     * Provide backwords compatibility
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, array('exception', 'request', 'type'))) {
            return $this->{'_' . $key};
        }
    }
    
    /**
     * Isset
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        if (in_array($key, array('exception', 'request', 'type'))) {
            return isset($this->{'_' . $key});
        }
    }
}