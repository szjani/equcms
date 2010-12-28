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
 * @see Zym_Navigation_Container
 */
require_once 'Zym/Navigation/Container.php';

/**
 * Zym_Navigation
 * 
 * Class for managing a hierarchical structure of Zym_Navigation_Page pages.  
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Navigation extends Zym_Navigation_Container
{
    /**
     * Creates a new navigation container
     *
     * @param array|Zend_Config $pages  [optional] pages to add
     */
    public function __construct($pages = null)
    {
        if (is_array($pages) || $pages instanceof Zend_Config) {
            $this->addPages($pages);
        } elseif (null !== $pages) {
            $msg = '$pages must be an array, Zend_Config or null';
            throw new InvalidArgumentException($msg);
        }
    }
}
