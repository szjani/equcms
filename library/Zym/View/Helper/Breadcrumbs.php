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
 * Helper for printing breadcrumbs
 *
 * @category   Zym
 * @package    Zym_View
 * @subpackage Helper
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_View_Helper_Breadcrumbs extends Zym_View_Helper_NavigationAbstract
{
    /**
     * Breadcrumbs separator string
     *
     * @var string
     */
    protected $_separator = ' &gt; ';

    /**
     * Minimum depth required to render breadcrumbs
     *
     * @var int
     */
    protected $_minDepth = 1;

    /**
     * Whether last page in breadcrumb should be hyperlinked
     *
     * @var bool
     */
    protected $_linkLast = false;

    /**
     * Retrieves helper and optionally sets container to operate on
     *
     * @param  Zym_Navigation_Container $container  [optional] container to
     *                                              operate on
     * @return Zym_View_Helper_Navigation
     */
    public function breadcrumbs(Zym_Navigation_Container $container = null)
    {
        if (null !== $container) {
            $this->_container = $container;
        }

        return $this;
    }

    /**
     * Sets breadcrumbs separator
     *
     * @param  string $separator
     * @return void
     */
    public function setSeparator($separator)
    {
        if (is_string($separator)) {
            $this->_separator = $separator;
        }
    }

    /**
     * Returns breadcrumbs separator
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->_separator;
    }

    /**
     * Sets minimum depth required to render breadcrumbs
     *
     * @param  int $minDepth  min depth required to render
     * @return void
     */
    public function setMinDepth($minDepth)
    {
        $this->_minDepth = (int)$minDepth;
    }

    /**
     * Returns minimum depth required to render breadcrumbs
     *
     * @return int
     */
    public function getMinDepth()
    {
        return $this->_minDepth;
    }

    /**
     * Sets whether last page in breadcrumb should be hyperlinked
     *
     * @param  bool $linkLast  whether last page should be hyperlinked
     * @return void
     */
    public function setLinkLast($linkLast)
    {
        $this->_linkLast = (bool)$linkLast;
    }

    /**
     * Returns whether last page in breadcrumb should be hyperlinked
     *
     * @return unknown
     */
    public function getLinkLast()
    {
        return $this->_linkLast;
    }

    /**
     * Render breadcrumbs for a navigation container
     *
     * @param  Zym_Navigation_Container $container  [optional] container to get
     *                                              breadcrumbs from, defaults
     *                                              to what is registered in the
     *                                              helper
     * @param  string|int               $indent     [optional] indentation
     * @return string
     */
    public function renderBreadcrumbs(Zym_Navigation_Container $container = null,
                                      $indent = null)
    {
        $indent = (null !== $indent)
                ? $this->_getWhitespace($indent)
                : $this->getIndent();

        if (null === $container) {
            $container = $this->getNavigation();
        }

        // init html
        $html = '';

        // stuff to use in the two steps below
        $found = false;
        $depth = -1;
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::CHILD_FIRST);

        // step 1: find the deepest active page
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

        // step 2: walk back to root
        if ($depth >= $this->_minDepth) {
            // put the current page last
            if ($this->_linkLast) {
                $html = $this->getPageAnchor($found);
            } else {
                $html = $found->getLabel();

                // translate if possible
                if ($this->_useTranslator && $t = $this->_getTranslator()) {
                    $html = $t->translate($html);
                }
            }

            // loop parents and prepend
            while ($parent = $found->getParent()) {
                if ($parent instanceof Zym_Navigation_Page) {
                    $html = $this->getPageAnchor($parent)
                          . $this->getSeparator()
                          . $html;
                }
               
                if ($parent === $container) {
                    // break if at the root of the given container
                    break;
                }

                $found = $parent;
            }
        }

        return strlen($html) ? $indent . $html . "\n" : "\n";
    }

    /**
     * Renders the registered container
     *
     * @param string|int $indent  [optional]
     * @return string
     */
    public function toString($indent = null)
    {
        return $this->renderBreadcrumbs(null, $indent);
    }
}
