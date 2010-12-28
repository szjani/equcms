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
 * @package Zym_Controller
 * @subpackage Request
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Controller_Request_Http
 */
require_once 'Zend/Controller/Request/Http.php';

/**
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Controller
 * @subpackage Request
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Controller_Request_Http extends Zend_Controller_Request_Http
{
    /**
     * Get http scheme
     *
     * @return string
     */
    public function getRequestScheme()
    {
        trigger_error('Zym_Controller_Request_Http::getRequestScheme() is deprecated, use getScheme() instead. This method will be removed in the next release');
        return $this->getScheme();
    }

    /**
     * Get http host
     *
     * @return string
     */
    public function getRequestHost()
    {
        trigger_error('Zym_Controller_Request_Http::getRequestHost() is deprecated, use getHttpHost() instead. This method will be removed in the next release');
        return $this->getHttpHost();
    }
}