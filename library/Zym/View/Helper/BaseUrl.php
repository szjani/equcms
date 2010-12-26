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
 * BaseUrl helper
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @package Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Helper_BaseUrl
{
    /**
     * BaseUrl
     *
     * @var string
     */
    private $_baseUrl;

    /**
     * Returns site's base url
     *
     * $file is appended to the base url for simplicity
     *
     * @param string $file
     * @return string
     */
    public function baseUrl($file = null)
    {
        // Get baseUrl
        $baseUrl = $this->getBaseUrl();

        // Remove trailing slashes
        $file = ($file !== null) ? '/' . ltrim($file, '/\\') : null;

        return $baseUrl . $file;
    }

    /**
     * Set BaseUrl
     *
     * @param string $base
     * @return Zym_View_Helper_BaseUrl
     */
    public function setBaseUrl($base)
    {
        $this->_baseUrl = rtrim($base, '/\\');
        return $this;
    }

    /**
     * Get BaseUrl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            /**
             * @see Zend_Controller_Front
             */
            require_once 'Zend/Controller/Front.php';
            $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

            // Remove scriptname eg... index.php from baseUrl
            $baseUrl = $this->_removeScriptName($baseUrl);

            $this->setBaseUrl($baseUrl);
        }

        return $this->_baseUrl;
    }

    /**
     * Remove Script filename from baseurl
     *
     * @param string $url
     * @return string
     */
    private function _removeScriptName($url)
    {
        if (!isset($_SERVER['SCRIPT_NAME'])) {
            // We can't do much now can we? (Well, we could parse out by ".")
            return $url;
        }

        if (($pos = strripos($url, basename($_SERVER['SCRIPT_NAME']))) !== false) {
            $url = substr($url, 0, $pos);
        }

        return $url;
    }
}