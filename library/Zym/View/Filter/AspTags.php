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
 * @subpackage Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_View_Filter_Interface
 */
require_once 'Zym/View/Filter/Interface.php';

/**
 * Converts asp tags to full <?php
 *
 * @author     Geoffrey Tran
 * @license    http://www.zym-project.com/license New BSD License
 * @package    Zym_View
 * @subpackage Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Filter_AspTags implements Zym_View_Filter_Interface
{
    /**
     * Filter
     *
     * @param string $buffer
     * @return string
     */
    public function filter($buffer)
    {
        // Don't parse if short_tags is enabled
        if (ini_get('asp_tags')) {
            return $buffer;
        }

        $pattern = array(
            '/<\%(?:=)?(?:\s)*(.*?)(?:;?\s*)\%>/xisS', // <%= handling
        );

        $out = preg_replace_callback($pattern, array($this, '_callBack'), $buffer);

        return $out;
    }

    /**
     * Preg callback
     *
     * @param array $match
     * @return string
     */
    protected function _callBack(array $match)
    {
        // Split up into readable vars
        list($full, $body) = $match;

        // Parse <%=
        if ($this->_isEcho($full)) {
            return "<?php echo $body; ?>";
        }

        return "<?php $body; ?>";
    }

    /**
     * Check if a string is an echo tag
     *
     * <%= %> style
     *
     * @param string $string
     * @return boolean
     */
    protected function _isEcho($string)
    {
        return (substr($string, 0, 3) === '<%=');
    }
}