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
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * Return an xml declaration
 * Useful for compatibility with php short_tags
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @package Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Helper_XmlDeclaration
{
    /**
     * Return xml declaration
     *
     * @param string $version
     * @param string $encoding
     * @param string $standalone
     * @return string
     */
    public function xmlDeclaration($version = '1.0', $encoding = 'UTF-8', $standalone = null)
    {
        $attrs = array(
            'version'    => is_numeric($version) ? sprintf('%01.1f', $version) // Handle int/float input
                                                 : $version,
            'encoding'   => $encoding,
            'standalone' => $standalone
        );

        // Assemble the string
        $attrString = '';
        foreach ($attrs as $key => $value) {
            if ($value !== null) {
                $attrString .= sprintf('%s="%s" ', $key, $value);
            }
        }

        return "<?xml $attrString?>";
    }
}