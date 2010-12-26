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
 * @package    Zym_Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zym_Filter_SentenceLength
 */
require_once 'Zym/Filter/SentenceLength.php';

/**
 * Filters a string for using in a URL
 * 
 * @category   Zym
 * @package    Zym_Filter
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Filter_UrlString extends Zym_Filter_SentenceLength
{
    /**
     * Whether slashes should be urlencoded
     *
     * @var bool
     */
    protected $_encodeSlashes = false;
    
    /**
     * Separator to use between words
     *
     * @var string
     */
    protected $_wordSeparator = '-';
    
    /**
     * Sets filter options
     *
     * @param int $maxLength  [optional] defaults to 128
     * @param bool $encodeSlashes  [optional] whether slashes should be
     *                             urlencoded, default is false
     * @param bool $replaceWhitespace [optional] whether repeated whitespace
     *                                should be removed, default is true
     * @param string $wordSeparator  [optional] separator to use between words
     * @return void
     */
    public function __construct($maxLength = null,
                                $encodeSlashes = null,
                                $replaceWhitespace = null,
                                $wordSeparator = null)
    {
        parent::__construct($maxLength, $replaceWhitespace);
        
        if (is_bool($encodeSlashes)) {
            $this->_encodeSlashes = $encodeSlashes;
        }
        
        if (null !== $wordSeparator) {
            $this->setWordSeparator($wordSeparator);
        }
    }
    
    /**
     * Sets separator to use between words
     *
     * @param string $wordSeparator
     */
    public function setWordSeparator($wordSeparator)
    {
        if (is_string($wordSeparator)) {
            $this->_wordSeparator = $wordSeparator;
        }
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the filtered string of $value
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        $value = parent::filter($value);
        $value = str_replace(' ', $this->_wordSeparator, $value);
        $value = urlencode($value);
        return $this->_encodeSlashes ? $value : str_replace('%2F', '/', $value);
    }
}