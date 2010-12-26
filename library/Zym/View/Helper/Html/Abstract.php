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
 * @package Zym_View
 * @subpackage Helper_Html
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_View_Helper_Abstract
 */
require_once 'Zym/View/Helper/Abstract.php';

/**
 * Abstract view helper
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @package Zym_View
 * @subpackage Helper_Html
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
abstract class Zym_View_Helper_Html_Abstract extends Zym_View_Helper_Abstract
{
    /**
     * Newline
     */
    const NEWLINE = "\n";

    /**
     * Indentation string
     * 
     * @var string
     */
    protected $_indent = '';
    
    /**
     * The tag closing bracket
     *
     * @var string
     */
    protected $_closingBracket = null;

    /**
     * Get the tag closing bracket
     *
     * @return string
     */
    public function getClosingBracket()
    {
        if (!$this->_closingBracket) {
            if ($this->_isXhtml()) {
                $this->_closeBracket = ' />';
            } else {
                $this->_closeBracket = '>';
            }
        }

        return $this->_closingBracket;
    }

    /**
     * Is doctype XHTML?
     *
     * @return boolean
     */
    protected function _isXhtml()
    {
        $doctype = $this->getView()->doctype();
        return $doctype->isXhtml();
    }

    /**
     * Converts an associative array to a string of tag attributes.
     *
     * @access public
     *
     * @param array $attribs From this array, each key-value pair is
     * converted to an attribute name and value.
     *
     * @return string The XHTML for the attributes.
     */
    protected function _htmlAttribs(array $attribs)
    {
        $view = $this->getView();

        $xhtml = '';
        foreach ($attribs as $key => $val) {
            $key = $view->escape($key);

            if (is_array($val)) {
                $val = implode(' ', $val);
            } else if ($val === null) {
                continue;
            }

            $val = $view->escape($val);

            $xhtml .= " $key=\"$val\"";
        }

        return substr($xhtml, 1);
    }

    /**
     * Retrieve whitespace representation of $indent
     * 
     * @param  int|string $indent 
     * @return string
     */
    protected function _getWhitespace($indent)
    {
        if (is_int($indent)) {
            $indent = str_repeat(' ', $indent);
        }

        return (string) $indent;
    }

    /**
     * Set the indentation string for __toString() serialization,
     * optionally, if a number is passed, it will be the number of spaces
     *
     * @param  string|int $indent
     * @return Zym_View_Helper_Html_Abstract
     */
    public function setIndent($indent)
    {
        $this->_indent = $this->_getWhitespace($indent);
        return $this;
    }

    /**
     * Retrieve indentation
     *
     * @return string
     */
    public function getIndent()
    {
        return $this->_indent;
    }
}