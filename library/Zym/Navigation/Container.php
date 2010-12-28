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
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * Zym_Navigation_Container
 * 
 * Container class for Zym_Navigation_Page classes.  
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
abstract class Zym_Navigation_Container
    implements RecursiveIterator, Countable
{
    /**
     * Contains sub pages
     *
     * @var array
     */
    protected $_pages = array();

    /**
     * Order in which to display and iterate pages
     * 
     * @var array
     */
    protected $_order = array();

    /**
     * Whether internal order has been updated
     * 
     * @var bool
     */
    protected $_orderUpdated = false;
    
    /**
     * Parent container
     *
     * @var Zym_Navigation_Container
     */
    protected $_parent = null;
    
    // Internal methods:

    /**
     * Sort pages according to their given positions
     * 
     * @return void
     */
    protected function _sort()
    {
        if ($this->_orderUpdated) {
            $newOrder = array();
            $index = 0;
            
            foreach ($this->_pages as $hash => $page) {
                $pos = $page->getPosition();
                if ($pos === null) {
                    $newOrder[$hash] = $index;
                    $index++;
                } else {
                    $newOrder[$hash] = $pos;
                }
            }

            asort($newOrder);
            $this->_order = $newOrder;
            $this->_orderUpdated = false;
        }
    }
    
    // Public methods:
    
    /**
     * Notifies container that the order of pages are updated
     *
     * @return void
     */
    public function notifyOrderUpdated()
    {
        $this->_orderUpdated = true;
    }
    
    /**
     * Adds a page to the container
     * 
     * @param  Zym_Navigation_Page|array|Zend_Config $page  page to add
     * @return Zym_Navigation_Container
     * @throws InvalidArgumentException  if invalid page is given
     */
    public function addPage($page)
    {
        if (is_array($page) || $page instanceof Zend_Config) {
            require_once 'Zym/Navigation/Page.php';
            $page = Zym_Navigation_Page::factory($page);
        } elseif (!$page instanceof Zym_Navigation_Page) {
            $msg = '$page must be Zym_Navigation_Page|array|Zend_Config';
            throw new InvalidArgumentException($msg);
        }
        
        $id = spl_object_hash($page);
        
        if (array_key_exists($id, $this->_order)) {
            return $this;
        }
        
        $this->_pages[$id] = $page;
        $this->_order[$id] = $page->getPosition();
        $this->_orderUpdated = true;
        
        $page->setParent($this);
        
        return $this;
    }
    
    /**
     * Adds several pages at once
     *
     * @param  array|Zend_Config $pages  pages to add
     * @return Zym_Navigation_Container
     * @throws InvalidArgumentException  if $pages is not array or Zend_Config
     */
    public function addPages($pages)
    {
        if ($pages instanceof Zend_Config) {
            $pages = $pages->toArray();
        }
        
        if (!is_array($pages)) {
            $msg = '$pages must be an array or a Zend_Config object';
            throw new InvalidArgumentException($msg);
        }
        
        foreach ($pages as $page) {
            $this->addPage($page);
        }
        
        return $this;
    }
    
    /**
     * Sets pages this container should have, clearing existing ones
     *
     * @param array $pages  pages to set
     * @return Zym_Navigation_Container
     */
    public function setPages(array $pages)
    {
        $this->removePages();
        return $this->addPages($pages);
    }
    
    /**
     * Removes the given page from the container
     *
     * @param  int|Zym_Navigation_Page $page  page to remove, either
     *                                                 position or instance
     * @return bool  indicating whether the removal was successful
     */
    public function removePage($page)
    {
        $this->_sort();
        
        if (is_int($page)) {
            $hash = array_search($page, $this->_order);
        } elseif ($page instanceof Zym_Navigation_Page) {
            $hash = spl_object_hash($page);
        } else {
            return false;
        }
        
        if (isset($this->_order[$hash])) {
            unset($this->_order[$hash]);
            unset($this->_pages[$hash]);
            $this->_orderUpdated = true;
            return true;
        }
        
        return false;
    }
    
    /**
     * Removes all pages in container
     *
     * @return Zym_Navigation_Container_Abstract
     */
    public function removePages()
    {
        $this->_pages = array();
        $this->_order = array();
        return $this;
    }
    
    /**
     * Checks if the container has the given page
     *
     * @param  Zym_Navigation_Page $page
     * @param  bool                $recursive [optional] defaults to false
     * @return bool
     */
    public function hasPage(Zym_Navigation_Page $page, $recursive = false)
    {
        $hash = spl_object_hash($page);
        
        if (array_key_exists($hash, $this->_order)) {
            return true;
        } elseif ($recursive) {
            foreach ($this->_pages as $childPage) {
                if ($childPage->hasPage($page, true)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Returns true if container contains any pages
     *
     * @return bool
     */
    public function hasPages()
    {
        return count($this->_order) > 0;
    }
    
    /**
     * Sets parent container
     *
     * @param  Zym_Navigation_Container $parent  [optional] new parent to set,
     *                                           defaults to null which will set
     *                                           no parent
     * @return Zym_Navigation_Page
     */
    public function setParent(Zym_Navigation_Container $parent = null)
    {
        // return if the given parent already is parent
        if ($parent === $this->_parent) {
            return $this;
        }
        
        // remove from old parent if page
        if (null !== $this->_parent && $this instanceof Zym_Navigation_Page) {
            $this->_parent->removePage($this);
        }
        
        // set new parent
        $this->_parent = $parent;
        
        // add to parent if page and not already a child
        if (null !== $this->_parent && $this instanceof Zym_Navigation_Page) {
            $this->_parent->addPage($this);
        }
        
        return $this;
    }
    
    /**
     * Returns parent container
     *
     * @return Zym_Navigation_Container|null
     */
    public function getParent()
    {
        return $this->_parent;
    }
    
    /**
     * Returns a child page matching $property == $value, or null if not found
     *
     * @param string $property  name of property to match against
     * @param mixed  $value     value to match property against
     * @return Zym_Navigation_Page|null  matching page or null
     */
    public function findOneBy($property, $value)
    {
        $iterator = new RecursiveIteratorIterator($this,
                            RecursiveIteratorIterator::SELF_FIRST);
        
        foreach ($iterator as $page) {
            if ($page->get($property) == $value) {
                return $page;
            }
        }
        
        return null;
    }
    
    /**
     * Returns all child pages matching $property == $value, or an empty array
     * if not found
     *
     * @param string $property  name of property to match against
     * @param mixed  $value     value to match property against
     * @return array  containing only Zym_Navigation_Page elements
     */
    public function findAllBy($property, $value)
    {
        $found = array();
        
        $iterator = new RecursiveIteratorIterator($this,
                            RecursiveIteratorIterator::SELF_FIRST);
        
        foreach ($iterator as $page) {
            if ($page->get($property) == $value) {
                $found[] = $page;
            }
        }
        
        return $found;
    }
    
    /**
     * Returns page(s) matching $property == $value
     *
     * @param string $property  name of property to match against
     * @param mixed  $value     value to match property against
     * @param bool   $all       [optional] whether an array of all matching
     *                          pages should be returned, or only the first.
     *                          If true, an array will be returned, even if not
     *                          matching pages are found. If false, null will be
     *                          returned if no matching page is found. Default
     *                          is false.
     */
    public function findBy($property, $value, $all = false)
    {
        if ($all) {
            return $this->findAllBy($property, $value);
        } else {
            return $this->findOneBy($property, $value);
        }
    }
    
    /**
     * Magic overload: Proxy calls to finder methods
     * 
     * Examples of finder calls:
     * <code>
     * // METHOD                    // SAME AS
     * $nav->findByLabel('foo');    // $nav->findOneBy('label', 'foo');
     * $nav->findOneByLabel('foo'); // $nav->findOneBy('label', 'foo');
     * $nav->findAllById('foo');    // $nav->findAllBy('id', 'foo');
     * </code>
     *
     * @param string $method     method name
     * @param array  $arguments  method arguments
     * @throws BadMethodCallException  if method does not exist
     */
    public function __call($method, $arguments)
    {
        if (@preg_match('/(find(?:One|All)?By)(.+)/', $method, $match)) { 
            return $this->{$match[1]}($match[2], $arguments[0]);
        }
        
        $msg = sprintf('Unknown method %s::%s', get_class($this), $method);
        throw new BadMethodCallException($msg);
    }
    
    /**
     * Returns an array representation of all pages in container
     *
     * @return array
     */
    public function toArray()
    {
        $pages = array();
        
        foreach ($this->_pages as $page) {
            $pages[] = $page->toArray();
        }
        
        return $pages;
    }
 
    // RecursiveIterator interface:

    /**
     * RecursiveIterator: Returns current page
     * 
     * @return Zym_Navigation_Page
     * @throws OutOfBoundsException  if the index is invalid
     */
    public function current()
    {
        $this->_sort();
        current($this->_order);
        $key = key($this->_order);
        
        if (isset($this->_pages[$key])) {
            return $this->_pages[$key];
        } else {
            $msg = 'Corruption detected in container; '
                 . 'invalid key found in internal iterator';
            throw new OutOfBoundsException($msg);
        }
    }

    /**
     * RecursiveIterator: Returns current page id
     * 
     * @return string
     */
    public function key()
    {
        $this->_sort();
        return key($this->_order);
    }

    /**
     * RecursiveIterator: Move pointer to next page in container
     * 
     * @return void
     */
    public function next()
    {
        $this->_sort();
        next($this->_order);
    }

    /**
     * RecursiveIterator: Moves pointer to beginning of container
     * 
     * @return void
     */
    public function rewind()
    {
        $this->_sort();
        reset($this->_order);
    }

    /**
     * RecursiveIterator: Determines if container is valid
     * 
     * @return bool
     */
    public function valid()
    {
        $this->_sort();
        return (current($this->_order) !== false);
    }
    
    /**
     * RecursiveIterator: Proxy to hasPages()
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->hasPages();
    }
    
    /**
     * RecursiveIterator: Returns pages
     *
     * @return Zym_Navigation_Page|null
     */
    public function getChildren()
    {
        $key = key($this->_order);
        
        if (isset($this->_pages[$key])) {
            return $this->_pages[$key];
        }
        return null;
    }
    
    // Countable interface:

    /**
     * Countable: Count of pages that are iterable
     * 
     * @return int
     */
    public function count()
    {
        return count($this->_order);
    }
}