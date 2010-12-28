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
 * @see Zym_Navigation_Iterator_Dfs
 */
require_once 'Zym/Navigation/Iterator/Dfs.php';

/**
 * Zym_Navigation_Iterator_Visible
 * 
 * Iterator class for filtering out pages in a Zym_Navigation_Iterator_Dfs
 * that are not visible. This class serves an example class for subclassing
 * the FilterIterator.
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @subpackage Zym_Navigation_Iterator
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Navigation_Iterator_Visible extends FilterIterator
{
    /**
     * Whether page should be considered invisible if parent is invisible
     *
     * @var bool
     */
    protected $_parentDependent = true;
    
    /**
     * Creates a FilterIterator for Zym_Navigation_Iterator_Dfs
     *
     * @param Zym_Navigation_Iterator_Dfs $iterator  iterator to iterate
     * @param bool $parentDependent  [optional] whether page should be
     *                               invisible if parent is invisible,
     *                               defaults to true
     */
    public function __construct(Zym_Navigation_Iterator_Dfs $iterator,
                               $parentDependent = null)
    {
       parent::__construct($iterator);
       if (is_bool($parentDependent)) {
           $this->_parentDependent = $parentDependent;
       }
    }
    
    /**
     * Filters out pages that are not visible
     *
     * @return bool
     */
    public function accept()
    {
        return $this->current()->isVisible($this->_parentDependent);
    }
}
