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
 * @see Zym_Navigation_Container
 */
require_once 'Zym/Navigation/Container.php';

/**
 * Used in the factory method
 * 
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * Zym_Navigation_Page
 * 
 * Base class for Zym_Navigation_Page pages.
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @subpackage Zym_Navigation_Page
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
abstract class Zym_Navigation_Page extends Zym_Navigation_Container
{
    /**
     * Page label
     *
     * @var string|null
     */
    protected $_label;
    
    /**
     * Page id
     *
     * @var string|null
     */
    protected $_id = null;
    
    /**
     * Style class for this page (CSS)
     *
     * @var string|null
     */
    protected $_class = null;
    
    /**
     * A more descriptive title for this page
     *
     * @var string
     */
    protected $_title = null;
    
    /**
     * This page's target
     *
     * @var string
     */
    protected $_target = null;
    
    /**
     * Page position (used by containers)
     *
     * @var int
     */
    protected $_position = null;
    
    /**
     * ACL resource assigned to this page
     * 
     * @var string|Zend_Acl_Resource_Interface
     */
    protected $_resource = null;
    
    /**
     * ACL privilege assigned to this page
     *
     * @var string
     */
    protected $_privilege = null;
    
    /**
     * Whether this page should be considered active
     *
     * @var bool
     */
    protected $_active = false;
    
    /**
     * Whether this page should be visible
     *
     * @var bool
     */
    protected $_visible = true;
    
    /**
     * Custom page properties, used by __set(), __get() and __isset()
     *
     * @var array
     */
    protected $_properties = array();
    
    // Initialization:
    
    /**
     * Factory for Zym_Navigation_Page classes
     *
     * @param  array|Zend_Config $options  options used to determine and
     *                                     construct page from  
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if $options is not array/Zend_Config
     * @throws InvalidArgumentException  if 'type' is given, and the specified
     *                                   type does not extend this class
     * @throws UnexpectedValueException  if not enough options are given to
     *                                   determine what page to construct
     * @throws Zend_Exception  if 'type' is specified and Zend_Loader is unable
     *                         to load the class
     */
    public static function factory($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        
        if (!is_array($options)) {
            $msg = '$options must be an array or Zend_Config';
            throw new InvalidArgumentException($msg);
        }
        
        if (isset($options['type'])) {
            $type = $options['type'];
            if (is_string($type) && !empty($type)) {
                switch (strtolower($type)) {
                    case 'mvc':
                        $type = 'Zym_Navigation_Page_Mvc';
                        break;
                    case 'uri':
                        $type = 'Zym_Navigation_Page_Uri';
                        break;
                }
                
                @Zend_Loader::loadClass($type);
                
                $page = new $type($options);
                if (!$page instanceof Zym_Navigation_Page) {
                    $msg = "$type does not extend Zym_Navigation_Page";
                    throw new InvalidArgumentException($msg);
                }
                return $page;
            }
        }
        
        if (isset($options['action']) || isset($options['controller'])) {
            
        } elseif (isset($options['uri'])) {
            require_once 'Zym/Navigation/Page/Uri.php';
            return new Zym_Navigation_Page_Uri($options);
        }
        
        require_once 'Zym/Navigation/Page/Mvc.php';
        return new Zym_Navigation_Page_Mvc($options);
    }
    
    /**
     * Creates a page
     *
     * @param  array|Zend_Config $options  requires 'label'
     * @throws InvalidArgumentException  if invalid options are given
     */
    public final function __construct($options)
    {
        // set options
        if (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options instanceof Zend_Config) {
            $this->setConfig($config);
        }
        
        // do custom initialization
        $this->_init();
        
        // validate page
        $this->_validate();
    }
    
    /**
     * Initialize page (used by subclasses)
     *
     */
    protected function _init()
    {
        
    }
    
    /**
     * Checks if the page is valid (has required properties)
     * 
     * Subclasses should overload this to add validation for its required
     * properties, and end the method by calling return parent::_isValid().
     *
     * @return void
     * @throws Zym_Navigation_Page_InvalidException  if page is invalid
     */
    protected function _validate()
    {
        if (!isset($this->_label)) {
            require_once 'Zym/Navigation/Exception.php';
            throw new Zym_Navigation_Exception('Label is not set');
        }
    }
    
    /**
     * Sets page properties using a Zend_Config object
     *
     * @param  Zend_Config $config  config object to get properties from
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if invalid options are given
     */
    public function setConfig(Zend_Config $config)
    {
        return $this->setOptions($config->toArray());
    }
    
    /**
     * Sets page properties using options from an associative array
     * 
     * Each key in the array corresponds to the according set*() method, and
     * each word is separated by underscores, e.g. the option 'target'
     * corresponds to setTarget(), and the option 'reset_params' corresponds to
     * the method setResetParams().
     *
     * @param  array $options  associative array of options to set
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if invalid options are given
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->set($key, $value);
        }
        
        return $this;
    }
    
    // Accessors:
    
    /**
     * Sets page label
     *
     * @param  string $label  new page label
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if empty/no string is given 
     */
    public function setLabel($label)
    {
        if (!is_string($label) || strlen($label) < 1) {
            $msg = '$label must be string with at least 1 character';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_label = $label;
        return $this;
    }
    
    /**
     * Returns page label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }
    
    /**
     * Sets page id
     *
     * @param  string|null $id  [optional] id to set, defaults to null
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if not given string or null
     */
    public function setId($id = null)
    {
        if (null !== $id && !is_string($id) && !is_numeric($id)) {
            $msg = '$id must be a string, number or null';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_id = null === $id ? $id : (string) $id;
        
        return $this;
    }
    
    /**
     * Returns page id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Sets page class (CSS)
     *
     * @param  string|null $class  [optional] class to set, defaults to null
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if not given string or null
     */
    public function setClass($class = null)
    {
        if (null !== $class && !is_string($class)) {
            $msg = '$class must be a string or null';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_class = $class;
        return $this;
    }
    
    /**
     * Returns page class (CSS)
     *
     * @return string|null
     */
    public function getClass()
    {
        return $this->_class;
    }
    
    /**
     * Sets page title
     *
     * @param  string $title  [optional] new page title, defaults to null,
     *                        which will set no title
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if not given string or null
     */
    public function setTitle($title = null)
    {
        if (null !== $title && !is_string($title)) {
            $msg = '$title must be a non-empty string';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_title = $title;
        return $this;
    }
    
    /**
     * Returns page title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->_title;
    }
    
    /**
     * Sets page target
     *
     * @param  string|null $target  [optional] target to set, defaults to null
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if not given string or null
     */
    public function setTarget($target = null)
    {
        if (null !== $target && !is_string($target)) {
            $msg = '$target must be a string or null';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_target = $target;
        return $this;
    }
    
    /**
     * Returns page target
     *
     * @return string|null
     */
    public function getTarget()
    {
        return $this->_target;
    }
    
    /**
     * Sets page position (used by containers)
     *
     * @param  int $position  [optional] defaults to null, which will reset
     *                        the position
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if $position is not integer or null
     */
    public function setPosition($position = null)
    {
        if (is_string($position)) {
            $temp = (int)$position;
            if ($temp < 0 || $temp > 0 || $position == '0') {
                $position = $temp;
            }
        }
        
        if (null !== $position && !is_int($position)) {
            $msg = '$position must be an integer or null, '
                 . 'or a string that casts to an integer';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_position = $position;
        
        // notify parent, if any
        if (isset($this->_parent)) {
            $this->_parent->notifyOrderUpdated();
        }
        
        return $this;
    }
    
    /**
     * Returns page position
     *
     * @return int|null
     */
    public function getPosition()
    {
        return $this->_position;
    }
    
    /**
     * Sets ACL resource assigned to this page
     * 
     * @param  null|string|Zend_Acl_Resource_Interface $resource  resource
     * @throws InvalidArgumentException  if $resource is invalid type
     * @return Zym_Navigation_Page
     */
    public function setResource($resource = null)
    {
        if (null === $resource || is_string($resource) || 
            $resource instanceof Zend_Acl_Role_Interface) {
            $this->_resource = $resource;
        } else {
            $msg = '$resource must be null|string|Zend_Acl_Resource_Interface';
            throw new InvalidArgumentException($msg);
        }
        
        return $this;
    }
    
    /**
     * Returns ACL resource assigned to page
     * 
     * @return string|Zend_Acl_Resource_Interface|null 
     */
    public function getResource()
    {
        return $this->_resource;
    }
    
    /**
     * Sets ACL privilege assigned to this page
     *
     * @param string|null $privilege  ACL privilege
     * @return Zym_Navigation_Page
     */
    public function setPrivilege($privilege = null)
    {
        $this->_privilege = is_string($privilege) ? $privilege : null;
        return $this;
    }
    
    /**
     * Returns ACL privilege assigned to this page
     *
     * @return string
     */
    public function getPrivilege()
    {
        return $this->_privilege;
    }
    
    /**
     * Sets whether page should be active or not
     *
     * @param  bool $active  [optional] whether page should be active or not,
     *                       defaults to true
     * @return Zym_Navigation_Page
     */
    public function setActive($active = true)
    {
        $this->_active = (bool)$active;
        return $this;
    }
    
    /**
     * Returns bool value indicating whether page is active or not
     *
     * @param  bool $recursive  [optional] whether page should be
     *                          considered active if any child pages
     *                          are active, defaults to false
     * @return bool
     */
    public function isActive($recursive = false)
    {
        if ($recursive) {
            if ($this->_active) {
                return true;
            } else {
                foreach ($this->_pages as $page) {
                    if ($page->isActive(true)) {
                        return true;
                    }
                }
                return false;
            }
        } else {
            return $this->_active;
        }
    }
    
    /**
     * Proxy to isActive()
     *
     * @param  bool $recursive  [optional] whether page should be
     *                          considered active if any child pages
     *                          are active, defaults to false
     * @return bool
     */
    public function getActive($recursive = false)
    {
        return $this->isActive($recursive);
    }
    
    /**
     * Sets whether the page should be visible or not
     *
     * @param  bool $visible  [optional] whether page should be visible or not,
     *                        defaults to true
     * @return Zym_Navigation_Page
     */
    public function setVisible($visible = true)
    {
        $this->_visible = (bool) $visible;
        return $this;
    }
    
    /**
     * Returns a boolean value indicating whether the page is visible
     *
     * @param  bool $parentDependent  [optional] whether page should be
     *                                considered invisible if parent
     *                                is invisible. defaults to false
     * @return bool
     */
    public function isVisible($parentDependent = false)
    {
        if ($parentDependent && isset($this->_parent) &&
            $this->_parent instanceof Zym_Navigation_Page) {
            if (!$this->_parent->isVisible(true)) {
                return false;
            }
        }
        
        return $this->_visible;
    }
    
    /**
     * Proxy to isVisible()
     * 
     * Returns a boolean value indicating whether the page is visible
     *
     * @param  bool $parentDependent  [optional] whether page should be
     *                                considered invisible if parent
     *                                is invisible. defaults to false
     * @return bool
     */
    public function getVisible($parentDependent = false)
    {
        return $this->isVisible($parentDependent);
    }
    
    /**
     * Normalizes a property name
     *
     * @param string $property  property name to normalize
     * @return string
     */
    protected function _normalizePropertyName($property)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
    }
    
    /**
     * Sets the given property
     * 
     * If the given property is native (id, class, title, etc), the matching
     * set method will be used. Otherwise, it will be set as a custom property.
     *
     * @param string $property  name of property to set
     * @param mixed  $value     value to set
     * @return Zym_Navigation_Page
     * @throws InvalidArgumentException  if property name is invalid
     */
    public function set($property, $value)
    {
        if (!is_string($property) || empty($property)) {
            $msg = 'property name must be a non-empty string';
            throw new InvalidArgumentException($msg);
        }
        
        $method = 'set' . $this->_normalizePropertyName($property);
        
        if ($method != 'setOptions' && $method != 'setConfig' &&
            method_exists($this, $method)) {
            $this->$method($value);
        } else {
            $this->_properties[$property] = $value;
        }
        
        return $this;
    }
    
    /**
     * Returns the value of the given property
     * 
     * If the given property is native (id, class, title, etc), the matching
     * get method will be used. Otherwise, it will return the matching custom
     * property, or null if not found.
     *
     * @param string $property  name of property to retrieve
     * @return mixed
     */
    public function get($property)
    {
        if (!is_string($property) || empty($property)) {
            $msg = 'property name must be a non-empty string';
            throw new InvalidArgumentException($msg);
        }
        
        $method = 'get' . $this->_normalizePropertyName($property);
        
        if (method_exists($this, $method)) {
            return $this->$method();
        } elseif (isset($this->_properties[$property])) {
            return $this->_properties[$property];
        }
        
        return null;
    }
    
    // Magic overloads:
    
    /**
     * Sets a custom property
     *
     * @param  string $name  property name
     * @param  mixed  $value value to set
     * @return void
     * @throws InvalidArgumentException  if $name is invalid as an index
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
    
    /**
     * Returns a property, or null if it doesn't exist
     *
     * @param  string $name  property name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }
    
    /**
     * Checks if a property is set
     * 
     * Returns true if the property is native (id, class, title, etc), and 
     * true or false if it's a custom property (depending on whether the 
     * property actually is set).
     *
     * @param string $name  property name
     * @return bool
     */
    public function __isset($name)
    {
        $method = 'get' . $this->_normalizePropertyName($name);
        if (method_exists($this, $method)) {
            return true;
        }
        
        return isset($this->_properties[$name]);
    }
    
    /**
     * Unsets the given custom property
     *
     * @param string $name  property name
     * @return void
     * @throws InvalidArgumentException  if the property is native
     */
    public function __unset($name)
    {
        $method = 'set' . $this->_normalizePropertyName($name);
        if (method_exists($this, $method)) {
            $msg = "Cannot unset native property '$name'";
            throw new InvalidArgumentException($msg);
        }
        
        if (isset($this->_properties[$name])) {
            unset($this->_properties[$name]);
        }
    }
    
    /**
     * Returns page label
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_label;
    }
    
    // Public methods:
    
    /**
     * Returns custom properties as an array
     *
     * @return array
     */
    public function getCustomProperties()
    {
        return $this->_properties;
    }
    
    /**
     * Returns an array representation of the page
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(
            $this->getCustomProperties(),
            array(
                'label'     => $this->getlabel(),
                'id'        => $this->getId(),
                'class'     => $this->getClass(),
                'title'     => $this->getTitle(),
                'target'    => $this->getTarget(),
                'position'  => $this->getPosition(),
                'resource'  => $this->getResource(),
                'privilege' => $this->getPrivilege(),
                'active'    => $this->isActive(),
                'visible'   => $this->isVisible(),
                'type'      => get_class($this),
                'pages'     => parent::toArray()
            ));
    }
    
    // Abstract methods:
    
    /**
     * Returns href for this page
     *
     * @return string
     */
    abstract public function getHref();
}
