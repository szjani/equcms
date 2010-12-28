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
 * @package    Zym_Validate
 * @subpackage Zym_Validate_Sitemap
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @see Zend_Uri
 */
require_once 'Zend/Uri.php';

/**
 * Validates whether a given value is valid as a sitemap <loc> value
 * 
 * @link       http://www.sitemaps.org/protocol.php Sitemaps XML format
 *
 * @category   Zym
 * @package    Zym_Validate
 * @subpackage Zym_Validate_Sitemap
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Validate_Sitemap_Loc extends Zend_Validate_Abstract
{
    /**
     * Validation key for not valid
     *
     */
    const NOT_VALID = 'invalidSitemapLoc';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_VALID => "'%value%' is not a valid sitemap location",
    );

    /**
     * Validates if a string is valid as a sitemap location
     * 
     * @link http://www.sitemaps.org/protocol.php#locdef <loc>
     *
     * @param  string  $value  value to validate
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        
        if (!is_string($value)) {
            return false;
        }
        
        return Zend_Uri::check($value);
    }
}