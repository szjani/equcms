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
 * @subpackage Router_Route
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Controller_Router_Route
 */
require_once 'Zend/Controller/Router/Route.php';

/**
 * Router used for http query parameters when /module/controller/action can't
 * be done due to lack of rewriting on servers.
 *
 * This route is useful when the only option you have is index.php?module=foo&controller=bar
 * Must be used to override the 'default' route to enable this feature as
 * it will any other routing will be useless
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Controller
 * @subpackage Router_Route
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Controller_Router_Route_HttpQuery extends Zend_Controller_Router_Route
{   
    /**
     * Current request values
     *
     * @var array
     */
    protected $_values = array();
    
    /**
     * Construct
     *
     */
    public function __construct()
    {}
    
    /**
     * Instantiates route based on passed Zend_Config structure
     *
     * @param Zend_Config $config Configuration object
     */
    public static function getInstance(Zend_Config $config)
    {
        return new self();
    }
    
    /**
     * Matches a user submitted path with parts defined by a map. Assigns and
     * returns an array of variables on a successful match.
     *
     * @param string Path used to match against this routing map
     * @return array|false An array of assigned values or a false on a mismatch
     */
    public function match($path)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $request         = $frontController->getRequest();
        
        if (!$request instanceof Zend_Controller_Request_Http) {
            return false;
        }
        
        // Request keys
        $moduleKey     = $request->getModuleKey();
        $controllerKey = $request->getControllerKey();
        $actionKey     = $request->getActionKey();

        // Defaults
        $moduleName     = $frontController->getDefaultModule();
        $controllerName = $frontController->getDefaultControllerName();
        $actionName     = $frontController->getDefaultAction();

        // Set a url path
        $module     = $request->getQuery($moduleKey, $moduleName);
        $controller = $request->getQuery($controllerKey, $controllerName);
        $action     = $request->getQuery($actionKey, $actionName);
        
        $this->_values = $request->getQuery();
        $params        = array();
        
        if ($module) {
            $params[$moduleKey] = $module;
        }
        
        if ($controller) {
            $params[$controllerKey] = $controller;
        }
        
        if ($action) {
            $params[$actionKey] = $action;
        }
        
        $this->_values = array_merge($this->_values, $params);
        
        return $params;
    }
    
    /**
     * Assembles user submitted parameters forming a URL path defined by this route
     * 
     * @param  array   $data An array of variable and value pairs used as parameters
     * @param  boolean $reset Whether or not to set route defaults with those provided in $data
     * 
     * @return string Route path with user submitted parameters
     */
    public function assemble($data = array(), $reset = false, $encode = false)
    {
        $frontController = Zend_Controller_Front::getInstance();
        
        // Defaults
        $moduleName     = $frontController->getDefaultModule();
        $controllerName = $frontController->getDefaultControllerName();
        $actionName     = $frontController->getDefaultAction();
        
        if (!$reset) {
            $data = array_merge($this->_values, $data);
        }
        
        $query = '';
        if (!empty($data)) {
            $queryParams = array();
            foreach ($data as $key => $val) {
                if ($encode) {
                   $queryParams[] = urlencode($key) . '=' . urlencode($val);
                } else {
                   $queryParams[] = $key . '=' . $val;
                }
            }
            
            $query .= '?' . implode('&', $queryParams);
        }
        
        return $query;
    }
}