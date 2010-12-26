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
 * @see Zym_View_Helper_NavigationAbstract
 */
require_once 'Zym/View/Helper/NavigationAbstract.php';

/**
 * Helper for printing menus as 'ul' HTML elements
 *
 * @category   Zym
 * @package    Zym_View
 * @subpackage Helper
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_View_Helper_Menu extends Zym_View_Helper_NavigationAbstract
{
    /**
     * CSS class to use for the ul element
     *
     * @var string
     */
    protected $_ulClass = 'navigation';
    
    /**
     * Whether a parent page should be active if a child is active
     *
     * @var bool
     */
    protected $_parentActive = true;

    /**
     * Retrieves helper and optionally sets container to operate on
     *
     * @param  Zym_Navigation_Container $container  [optional] container to
     *                                              operate on
     * @return Zym_View_Helper_Menu
     */
    public function menu(Zym_Navigation_Container $container = null)
    {
        if (null !== $container) {
            $this->setNavigation($container);
        }

        return $this;
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
            'class'  => $page->getClass()
        );

        $href = $page->getHref();

        if ($href) {
            $attribs['href'] = $href;
            $attribs['target'] = $page->getTarget();
            $element = 'a';
        } else {
            $element = 'span';
        }

        return '<' . $element . ' ' . $this->_htmlAttribs($attribs) . '>'
             . $this->getView()->escape($label)
             . '</' . $element . '>';
    }

    /**
     * Sets CSS class to use for ul elements
     *
     * @param  string $ulClass  class to set
     * @return Zym_View_Helper_Menu
     */
    public function setUlClass($ulClass)
    {
        if (is_string($ulClass)) {
            $this->_ulClass = $ulClass;
        }

        return $this;
    }

    /**
     * Returns CSS class to use for ul elements
     *
     * @return string
     */
    public function getUlClass()
    {
        return $this->_ulClass;
    }
    
    /**
     * Sets a flag indicating whether a parent page should be rendered as
     * active if a child page is active
     *
     * @param bool $flag
     * @return Zym_View_Helper_Menu
     */
    public function setParentActive($flag)
    {
        $this->_parentActive = (bool) $flag;
        return $this;
    }
    
    /**
     * Returns a flag indicating whether a parent page should be rendered as
     * active if a child is active
     *
     * @return bool
     */
    public function getParentActive()
    {
        return $this->_parentActive;
    }

    /**
     * Renders ul list menu for the given container
     *
     * @param  Zym_Navigation_Container $container  container to create
     *                                              menu from
     * @param  string|int               $indent     [optional] indentation
     * @param  bool                     $first      [optional] whether this
     *                                              container should be
     *                                              considered the first that is
     *                                              rendered, defaults to true
     * @return string
     */
    public function renderMenu(Zym_Navigation_Container $container,
                               $indent = null, $first = true)
    {
        $indent = (null !== $indent)
                ? $this->_getWhitespace($indent)
                : $this->getIndent();

        // init html
        $html = '';

        // loop pages
        foreach ($container as $page) {
            if (!$this->_accept($page, false)) {
                // page is not accepted
                continue;
            }

            // create li element for page
            $liCss = $page->isActive($this->getParentActive())
                   ? ' class="active"'
                   : '';
            $html .= "$indent    <li$liCss>\n";

            // create anchor element
            $html .= "$indent        {$this->getPageAnchor($page)}\n";

            // render sub pages, if any
            if ($page->hasPages()) {
                $html .= $this->renderMenu($page, "$indent        ", false);
            }

            // end li element
            $html .= "$indent    </li>\n";
        }

        // wrap items in a ul element
        // this is done so an empty list will not be created if
        // every (sub) page is invisible
        if (strlen($html)) {
            if ((bool)$first && strlen($this->_ulClass)) {
                $ulClass = " class=\"{$this->_ulClass}\"";
            } else {
                $ulClass = '';
            }
            $html = "$indent<ul$ulClass>\n$html$indent</ul>\n";
        }

        return $html;
    }

    /**
     * Renders the inner-most sub menu for the active page in the $container
     *
     * @param  Zym_Navigation_Container $container  [optional] container to
     *                                              render sub menu for,
     *                                              defaults to what is
     *                                              registered in the container
     * @param  string|int               $indent     [optional] indentation
     * @reutrn string
     */
    public function renderSubMenu(Zym_Navigation_Container $container = null,
                                  $indent = null)
    {
        
        if (null === $container) {
            $container = $this->getNavigation();
        }
        
        // stuff to use in the two steps below
        $found = false;
        $depth = -1;
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::CHILD_FIRST);
        
        // find the deepest active page
        foreach ($iterator as $page) {
            if (!$this->_accept($page)) {
                // page is not accepted
                continue;
            }
            if ($page->isActive() && $iterator->getDepth() > $depth) {
                $found = $page;
                $depth = $iterator->getDepth();
            }
        }
        
        if ($found) {
            if (count($found)) {
                return $this->renderMenu($found, $indent, false);
            }
            
            $parent = $found->getParent();
            if ($parent instanceof Zym_Navigation_Page) {
                
                return $this->renderMenu($parent, $indent, false);
            }
        }
        
        return '';
    }
    
    /**
     * Renders the registered container as a ul list
     *
     * @param string|int $indent  [optional]
     * @return string
     */
    public function toString($indent = null)
    {
        return $this->renderMenu($this->getNavigation(), $indent);
    }
}
