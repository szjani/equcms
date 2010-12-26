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
 * Returns current server url (w/ or w/o request URI)
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @package Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Helper_ServerUrl
{
    /**
     * Scheme
     *
     * @var string
     */
    protected $_scheme;

    /**
     * Host
     *
     * Including port
     *
     * @var string
     */
    protected $_host;

    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] === true)) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }
        $this->setScheme($scheme);

        if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
            $this->setHost($_SERVER['HTTP_HOST']);
        } else if (isset($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'])) {
            $name = $_SERVER['SERVER_NAME'];
            $port = $_SERVER['SERVER_PORT'];

            if (($scheme == 'http' && $port == 80) || ($scheme == 'https' && $port == 443)) {
                $this->setHost($name);
            } else {
                $this->setHost($name . ':' . $port);
            }
        }
    }

    /**
     * Server url
     *
     * Returns the current hosts url like http://site.com
     *
     * @return string
     */
    public function serverUrl($requestUri = null)
    {
        // Display request uri
        if ($requestUri === true) {
            $path = $_SERVER['REQUEST_URI'];
        } else if (is_string($requestUri)) {
            $path = $requestUri;
        } else {
            $path = '';
        }

        if ($path) {
            $path = '/' . ltrim($path, '/\\');
        }

        // Return url
        return $this->getScheme() . '://' . $this->getHost() . $path;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * Set host
     *
     * @param string $_host
     */
    public function setHost($host)
    {
        $this->_host = $host;
    }

    /**
     * Get scheme
     *
     * Http or https
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->_scheme;
    }

    /**
     * Set scheme
     *
     * @param string $_scheme
     */
    public function setScheme($scheme)
    {
        $this->_scheme = $scheme;
    }
}
