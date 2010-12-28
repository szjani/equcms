<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Zym
 * @package    Zym_View
 * @subpackage Helper
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zym_View_Helper_Html_Abstract
 */
require_once 'Zym/View/Helper/Html/Abstract.php';

/**
 * @see Zym_Navigation
 */
require_once 'Zym/Navigation.php';

/**
 * Base class for navigation related helpers
 *
 * @category   Zym
 * @package    Zym_View
 * @subpackage Helper
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */ 
abstract class Zym_View_Helper_NavigationAbstract extends Zym_View_Helper_Html_Abstract
{
    /**
     * Container to operate on
     * 
     * @var Zym_Navigation_Container
     */
    protected $_container;
    
    /**
     * ACL role to use when iterating pages
     * 
     * @var string|Zend_Acl_Role_Interface
     */
    protected $_role = null;
    
    /**
     * ACL to use when iterating pages
     * 
     * @var Zend_Acl
     */
    protected $_acl = null;
    
    /**
     * Default ACL role to use when iterating pages if not explicitly set
     * 
     * @var string|Zend_Acl_Role_Interface
     */
    protected static $_defaultRole = null;
    
    /**
     * Default ACL to use when iterating pages if not explicitly set
     * 
     * @var Zend_Acl
     */
    protected static $_defaultAcl = null;
    
    /**
     * Whether translator should be used
     * 
     * @var boolean
     */
    protected $_useTranslator = true;
    
    /**
     * Translator
     * 
     * @var Zend_Translate_Adapter
     */
    protected $_translator;
    
    /**
     * Proxy to the navigation container
     *
     * @param  string $method     method in the container to call
     * @param  array  $arguments  [optional] arguments to pass
     * @throws BadMethodCallException  if method does not exist in container
     */
    public function __call($method, $arguments = null)
    {
        $this->getNavigation();
        if (method_exists($this->_container, $method)) {
            return call_user_func_array(array($this->_container, $method), $arguments);
        } else {
            $msg = "Method '$method' does not exst in container";
            throw new BadMethodCallException($msg);
        }
    }
    
    /**
     * Sets navigation container to operate on
     *
     * @param  Zym_Navigation_Container $container  [optional] container to
     *                                              operate on, default is
     *                                              null, meaning it will be
     *                                              reset
     * @return Zym_View_Helper_NavigationAbstract
     */
    public function setNavigation(Zym_Navigation_Container $container = null)
    {
        $this->_container = $container;
        return $this;
    }
    
    /**
     * Returns navigation container
     *
     * @return Zym_Navigation_Container
     */
    public function getNavigation()
    {
        if (null === $this->_container) {
            $this->_retrieveDefaultNavigation();
        }
        
        return $this->_container;
    }
    
    /**
     * Retrieves default navigation container
     *
     * @return void
     */
    protected function _retrieveDefaultNavigation()
    {
        // try to fetch from registry first
        require_once 'Zend/Registry.php';
        if (Zend_Registry::isRegistered('Zym_Navigation')) {
            $nav = Zend_Registry::get('Zym_Navigation');
            if ($nav instanceof Zym_Navigation_Container) {
                $this->_container = $nav;
                return;
            }
        }
        
        // nothing found, create new container
        $this->_container = new Zym_Navigation();
    }
    
    /**
     * Returns HTML anchor for the given pages
     *
     * @param  Zym_Navigation_Page $page  page to get anchor for
     * @return string
     */
    public function getPageAnchor(Zym_Navigation_Page $page)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();
        
        if ($this->_useTranslator && $t = $this->_getTranslator()) {
            $label = $t->translate($label);
            $title = $t->translate($title);
        }
        
        // get attribs for anchor element
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'class'  => $page->getClass(),
            'href'   => $page->getHref(),
            'target' => $page->getTarget()
        );
        
        return '<a ' . $this->_htmlAttribs($attribs) . '>'
             . $this->getView()->escape($label)
             . '</a>';
    }
    
    /**
     * Sets boolean flag indicating whether translator should be used
     * 
     * @param bool $useTranslator  [optional] defaults to true
     * @return Zym_View_Helper_NavigationAbstract
     */
    public function setUseTranslator($useTranslator = true)
    {
        $this->_useTranslator = (bool) $useTranslator;
        return $this;
    }
    
    /**
     * Sets translator object to use
     * 
     * @param Zend_Translate|Zend_Translate_Adapter|null $translator
     */
    public function setTranslator($translator)
    {
        if (null === $translator || $translator instanceof Zend_Translate_Adapter) {
            $this->_translator = $translator;
        } elseif ($translator instanceof Zend_Translate) {
            $this->_translator = $translator->getAdapter();
        }
    }
    
    /**
     * Returns translator or null
     * 
     * @return Zend_Translate_Adapter|null
     */
    protected function _getTranslator()
    {
        if (null === $this->_translator) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $t = Zend_Registry::get('Zend_Translate');
                if ($t instanceof Zend_Translate) {
                    return $t->getAdapter();
                } elseif ($t instanceof Zend_Translate_Adapter) {
                    return $t;
                }
            }
        }
        
        return $this->_translator;
    }
    
    /**
     * Sets ACL to use when iterating pages
     * 
     * @param  Zend_Acl $acl  [optional] ACL object, defaults to null which
     *                        sets no ACL object
     * @return Zym_View_Helper_NavigationAbstract
     */
    public function setAcl(Zend_Acl $acl = null)
    {
        $this->_acl = $acl;
        return $this;
    }
    
    /**
     * Sets default ACL to use if another ACL is not explicitly set
     * 
     * @param  Zend_Acl $acl  [optional] ACL object, defaults to null which
     *                        sets no ACL object
     * @return void
     */
    public static function setDefaultAcl(Zend_Acl $acl = null)
    {
        self::$_defaultAcl = $acl;
    }
    
    /**
     * Returns ACL or null if it isn't set using {@link setAcl()} or 
     * {@link setDefaultAcl()}
     *
     * @return Zend_Acl|null
     */
    public function getAcl()
    {
        if ($this->_acl === null && self::$_defaultAcl !== null) {
            return self::$_defaultAcl;
        }
        
        return $this->_acl;
    }
    
    /**
     * Sets ACL role(s) to use when iterating pages
     * 
     * @param  null|string|Zend_Acl_Role_Interface $role   [optional] role to set,
     *                                                     defaults to null
     * @throws InvalidArgumentException  if $role is not null, string, or
     *                                   Zend_Acl_Role_Interface
     * @return Zym_View_Helper_NavigationAbstract
     */
    public function setRole($role = null)
    {
        if (null === $role || is_string($role) ||
            $role instanceof Zend_Acl_Role_Interface) {
            $this->_role = $role;
        } else {
            $msg = '$role must be null|string|Zend_Acl_Role_Interface';
            throw new InvalidArgumentException($msg);
        }
        
        return $this;
    }
    
    /**
     * Sets default ACL role(s) to use when iterating pages if not explicitly
     * set later with {@link setRole()}
     * 
     * @param  null|string|Zend_Acl_Role_Interface $role   [optional] role to set,
     *                                                     defaults to null
     * @throws InvalidArgumentException  if $role is not null, string, or
     *                                   Zend_Acl_Role_Interface
     * @return void
     */
    public static function setDefaultRole($role = null)
    {
        if (null === $role || is_string($role) ||
            $role instanceof Zend_Acl_Role_Interface) {
            self::$_defaultRole = $role;
        } else {
            $msg = '$role must be null|string|Zend_Acl_Role_Interface';
            throw new InvalidArgumentException($msg);
        }
    }
    
    /**
     * Returns ACL role to use when iterating pages, or null if it isn't set
     * using {@link setRole()} or {@link setDefaultRole()}
     * 
     * @return string|Zend_Acl_Role_Interface|null
     */
    public function getRole()
    {
        if ($this->_role === null && self::$_defaultRole !== null) {
            return self::$_defaultRole;
        }
        
        return $this->_role;
    }
    
    /**
     * Determines whether a page should be accepted when iterating using ACL
     * 
     * Validates that the role set in helper inherits or is the same as
     * the role(s) found in the page
     * 
     * @param Zym_Navigation_Page $page  page to check
     * @param bool $recursive  [optional] whether it should check recursively
     * @return bool
     */
    protected function _acceptAcl(Zym_Navigation_Page $page, $recursive = true)
    {
        if (!$acl = $this->getAcl()) {
            // no acl registered means don't use acl
            return true;
        }
        
        // do not accept by default
        $accept = false;
        
        // do not accept if helper has no role
        if ($role = $this->getRole()) {
            $resource = $page->getResource();
            $privilege = $page->getPrivilege();
            
            if ($resource || $privilege) {
                // determine using helper role and page resource/privilege
                $accept = $this->getAcl()->isAllowed($role, $resource, $privilege);
            } else {
                // accept if page has no resource or privilege
                $accept = true;
            }
        }
        
        // loop parent(s) recursively if page is accepted and recurisve is true
        if ($accept && $recursive) {
            $parent = $page->getParent();
            if ($parent instanceof Zym_Navigation_Page) {
                $accept = $this->_acceptAcl($parent, true);
            } 
        }
        
        return $accept;
    }
    
    /**
     * Determines whether a page should be accepted when iterating
     *
     * @param Zym_Navigation_Page $page  page to check
     * @param bool $recursive  [optional] whether it should check recursively
     */
    protected function _accept(Zym_Navigation_Page $page, $recursive = true)
    {
        // accept by default
        $accept = true;
        
        if (!$page->isVisible($recursive)) {
            // don't accept invisible pages
            $accept = false;
        } elseif (!$this->_acceptAcl($page, $recursive)) {
            // acl is not amused
            $accept = false;
        }
        
        return $accept;
    }
    
    /**
     * Renders helper
     * 
     * @param string|int $indent  [optional]
     * @return string
     */
    abstract public function toString($indent = null);
    
    /**
     * Magic method, proxy to toString()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
