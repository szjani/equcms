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
 * @subpackage Zym_Navigation_Iterator
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * Zym_Navigation_Iterator_Dfs
 * 
 * Depth-first-search iterator with pre-order traversal.
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @subpackage Zym_Navigation_Iterator
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Navigation_Iterator_Dfs extends RecursiveIteratorIterator
{
    /**
     * Creates a depth-first pre-order traversal iterator
     *
     * @param Zym_Navigation_Container $container  container to iterate
     */
    public function __construct(Zym_Navigation_Container $container)
    {
        parent::__construct($container, self::SELF_FIRST);
    }
}
