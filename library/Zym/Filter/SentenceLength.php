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
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Filters a sentence string
 * 
 * @category   Zym
 * @package    Zym_Filter
 * @author     Robin Skoglund <robinsk@gmail.com>
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Filter_SentenceLength implements Zend_Filter_Interface
{
    /**
     * String length must not exceed this
     *
     * @var int
     */
    protected $_maxLength = 128;

    /**
     * Whether repeated whitespace should be removed
     *
     * @var int
     */
    protected $_replaceWhitespace = true;
    
    /**
     * Sets filter options
     *
     * @param int $maxLength  [optional] defaults to 128
     * @param bool $replaceWhitespace [optional] whether repeated whitespace
     *                                should be removed, default is true
     * @return void
     */
    public function __construct($maxLength = null, $replaceWhitespace = null)
    {
        if (is_integer($maxLength) && $maxLength > 0) {
            $this->_maxLength = $maxLength;
        }
        
        if (is_bool($replaceWhitespace)) {
            $this->_replaceWhitespace = $replaceWhitespace;
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
        $value = (string) $value;

        if ($this->_replaceWhitespace) {
            // first, trim string
            $value = trim($value);
            
            // replace repeated whitespace with a single space
            $value = @preg_replace('/\s+/', ' ', $value);
        }
        
        if (strlen($value) > $this->_maxLength) {
            $arr = @split(' ', $value);
            
            if ($arr !== false) {
                $value = $arr[0];
                $count = count($arr);
                if ($count > 1) {
                    for ($i = 1; $i < $count; $i++) {
                        if ((strlen($value) + strlen($arr[$i]) + 1)
                            > $this->_maxLength) {
                            break;
                        } else {
                            $value .= " {$arr[$i]}";
                        }
                    }
                }
            }
            
            $value = substr($value, 0, $this->_maxLength);
        }
        
        return $value;
    }
}
