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
 * Validates whether a given value is valid as a sitemap <lastmod> value
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
class Zym_Validate_Sitemap_Lastmod extends Zend_Validate_Abstract
{
    /**
     * Regular expression to use when validating
     *
     */
    const LASTMOD_REGEX = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])(T([0-1][0-9]|2[0-3])(:[0-5][0-9]){2}(\\+|-)([0-1][0-9]|2[0-3]):[0-5][0-9])?$/m';
    
    /**
     * Validation key for not valid
     *
     */
    const NOT_VALID = 'invalidSitemapLastmod';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_VALID => "'%value%' is not a valid sitemap lastmod",
    );

    /**
     * Validates if a string is valid as a sitemap lastmod
     * 
     * @link http://www.sitemaps.org/protocol.php#lastmoddef <lastmod>
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
        
        return @preg_match(self::LASTMOD_REGEX, $value) == 1;
    }
    
}