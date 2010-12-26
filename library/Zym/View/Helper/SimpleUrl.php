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
 * @see Zend_View_Helper_Url
 */
require_once 'Zend/View/Helper/Url.php';

/**
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @package Zym_View
 * @subpackage Helper
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Helper_SimpleUrl extends Zend_View_Helper_Url
{
    /**
     * Create URL based on default route
     *
     * @param  string $action
     * @param  string $controller
     * @param  string $module
     * @param  array $params
     * @return string
     */
    public function simpleUrl($action, $controller = null, $module = null, array $params = array(), $encode = true)
    {
        if ($controller === null || $module === null) {
            /**
             * @see Zend_Controller_Front
             */
            require_once 'Zend/Controller/Front.php';
            $request = Zend_Controller_Front::getInstance()->getRequest();

            if ($request instanceof Zend_Controller_Request_Abstract) {
                // Get defaults
                if ($module === null) {
                    $module     = $request->getModuleName();
                }

                if ($controller === null) {
                    $controller = $request->getControllerName();
                }
            }
        }

        // Create url
        $urlOptions = array_merge($params, array('module'     => $module,
                                                 'controller' => $controller,
                                                 'action'     => $action));
        $url        = $this->url($urlOptions, 'default', true, $encode);

        return $url;
    }
}