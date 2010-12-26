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
 * @subpackage Response
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Controller_Response_Http
 */
require_once 'Zend/Controller/Response/Http.php';

/**
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Controller
 * @subpackage Response
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Controller_Response_Http extends Zend_Controller_Response_Http 
{
    /**
     * Cookies storage
     *
     * @var array
     */
    protected $_cookies = array();
    
    /**
     * Sets a cookie.
     *
     * @param string  $name      HTTP header name
     * @param string  $value     Value for the cookie
     * @param string  $expire    Cookie expiration period
     * @param string  $path      Path
     * @param string  $domain    Domain name
     * @param boolean $secure    If secure
     * @param boolean $httpOnly  If uses only HTTP (PHP 5.2 >=), not supported by all browsers
     * @return Zym_Controller_Response_Http
     */
    public function setCookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false, $httpOnly = false)
    {
        $this->canSendHeaders(true);
        
        // Validate expiration time
        if ($expire !== null && is_numeric($expire)) {
            $expire = (int) $expire;
        } else if ($expire !== null) {
            $expire = strtotime($expire);
            
            // Barf if its not valid
            if ($expire === false || $expire == -1) {
                /**
                 * @see Zym_Controller_Response_Exception
                 */
                require_once 'Zym/Controller/Response/Exception.php';
                throw new Zym_Controller_Response_Exception(
                    'Your expire parameter is not valid.'
                );
            }
        }
        
        $this->cookies[] = array(
            'name'     => $name,
            'value'    => $value,
            'expire'   => $expire,
            'path'     => $path,
            'domain'   => $domain,
            'secure'   => $secure ? true : false,
            'httpOnly' => $httpOnly
        );
        
        return $this;
    }
    
    /**
     * Clear cookies
     *
     * @return Zym_Controller_Response_Http
     */
    public function clearCookies()
    {
        $this->_cookies = array();
      
        return $this;
    }
    
    /**
     * Retrieves cookies from the current web response.
     *
     * @return array Cookies
     */
    public function getCookies()
    {
        $cookies = array();
        foreach ($this->cookies as $cookie){
            $cookies[$cookie['name']] = $cookie;
        }
    
        return $cookies;
    }

    /**
     * Return whether a cookie has been set
     *
     * @param string $name
     * @return boolean
     */
    public function hasCookie($name)
    {
        foreach ($this->_cookies as $cookie) {
            if ($cookie['name'] == $name) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Clear all headers, normal and raw
     *
     * @return Zym_Controller_Response_Http
     */
    public function clearAllHeaders()
    {
        // Clear cookies
        $this->clearCookies();
        
        return parent::clearAllHeaders();
    }
    
    /**
     * Send all headers
     *
     * Sends any headers specified. If an {@link setHttpResponseCode() HTTP response code}
     * has been specified, it is sent with the first header.
     *
     * @return Zym_Controller_Response_Http
     */
    public function sendHeaders()
    {
        // Only check if we can send headers if we have headers to send
        if (count($this->_cookies)) {
            $this->canSendHeaders(true);
        }
        
        // Send cookies
        foreach ($this->_cookies as $cookie) {
            setrawcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'],
                         $cookie['domain'], $cookie['secure'], $cookie['httpOnly']);
        }
        
        return parent::sendHeaders();
    }
}
