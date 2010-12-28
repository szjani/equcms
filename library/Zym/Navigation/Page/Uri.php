<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @subpackage Zym_Navigation_Page
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zym_Navigation_Page_Abstract
 */
require_once 'Zym/Navigation/Page.php';

/**
 * Zym_Navigation_Page_Uri
 * 
 * Represents a page that is defined by specifying a URI.  
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @subpackage Zym_Navigation_Page
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Navigation_Page_Uri extends Zym_Navigation_Page
{
    /**
     * Page URI
     *
     * @var string
     */
    protected $_uri = null;
    
    /**
     * Checks if the page is valid (has required properties)
     *
     * @return void
     * @throws Zym_Navigation_Exception  if page is invalid
     */
    protected function _validate()
    {
        if (!isset($this->_uri)) {
            $this->_uri = '';
        }
        
        parent::_validate();
    }
    
    /**
     * Sets page URI
     *
     * @param  string $uri page URI, must be at least one character
     * @throws InvalidArgumentException  if $uri is invalid
     */
    public function setUri($uri)
    {
        if (!is_string($uri)) {
            throw new InvalidArgumentException('$uri must be a string');
        }
        
        $this->_uri = $uri;
    }
    
    /**
     * Returns URI
     *
     * @return string
     */
    public function getUri()
    {
        return $this->_uri;
    }
    
    /**
     * Returns href for this page
     *
     * @return string
     */
    public function getHref()
    {
        return $this->getUri();
    }
    
    // Public methods:
    
    /**
     * Returns an array representation of the page
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            array(
                'uri' => $this->getUri()
            )); 
    }
}
