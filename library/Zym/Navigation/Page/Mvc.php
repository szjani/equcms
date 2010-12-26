<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @subpackage Zym_Navigation_Page
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zym_Navigation_Page
 */
require_once 'Zym/Navigation/Page.php';

/**
 * @see Zend_Controller_Action_HelperBroker
 */
require_once 'Zend/Controller/Action/HelperBroker.php';

/**
 * Used to check if page is active
 * 
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Front.php';

/**
 * Zym_Navigation_Page_Mvc
 * 
 * Represents a page that is defined using module, controller, action, route
 * name and route params to assemble the href  
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @subpackage Zym_Navigation_Page
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Navigation_Page_Mvc extends Zym_Navigation_Page
{
    /**
     * Action name
     *
     * @var string
     */
    protected $_action = null;
    
    /**
     * Controller name
     *
     * @var string
     */
    protected $_controller = null;
    
    /**
     * Module name
     *
     * @var string
     */
    protected $_module = null;
    
    /**
     * Params to use when assembling URL
     *
     * @see getHref()
     * @var array
     */
    protected $_params = array();
    
    /**
     * Route name
     * 
     * Used when assembling URL.
     *
     * @see getHref()
     * @var string
     */
    protected $_route = 'default';
    
    /**
     * Whether params should be reset when assembling URL
     *
     * @see getHref()
     * @var bool
     */
    protected $_resetParams = true;
    
    /**
     * Action helper for assembling URLs
     *
     * @see getHref()
     * @var Zend_Controller_Action_Helper_Url
     */
    protected static $_urlHelper = null;
    
    // Accessors:
    
    /**
     * Returns bool value indicating whether page is active or not
     * 
     * This method will compare the page against the request object.
     *
     * @param  bool $recursive  [optional] whether page should be
     *                          considered active if any child pages
     *                          are active, defaults to false
     * @return bool
     */
    public function isActive($recursive = false)
    {
        if (!$this->_active) {
            $front = Zend_Controller_Front::getInstance();
            $reqParams = $front->getRequest()->getParams();
            
            if (!array_key_exists('module', $reqParams)) {
                $reqParams['module'] = $front->getDefaultModule();
            }

            $myParams = $this->_params;
            
            if (null !== $this->_module) {
                $myParams['module'] = $this->_module;
            } else {
                $myParams['module'] = $front->getDefaultModule();
            }
            
            if (null !== $this->_controller) {
                $myParams['controller'] = $this->_controller;
            } else {
                $myParams['controller'] = $front->getDefaultControllerName();
            }
            
            if (null !== $this->_action) {
                $myParams['action'] = $this->_action;
            } else {
                $myParams['action'] = $front->getDefaultAction();
            }
            
            if (count(array_intersect_assoc($reqParams, $myParams)) ==
                count($myParams)) {
                return $this->_active = true;
            }
        }
        
        return parent::isActive($recursive);
    }
    
    /**
     * Returns href for this page
     *
     * @return string|null
     */
    public function getHref()
    {
        if (null === self::$_urlHelper) {
            self::$_urlHelper =
                Zend_Controller_Action_HelperBroker::getStaticHelper('url');
        }
        
        $params = $this->getParams();
        
        if ($tempParam = $this->getModule()) {
            $params['module'] = $tempParam;
        }
        
        if ($tempParam = $this->getController()) {
            $params['controller'] = $tempParam;
        }
        
        if ($tempParam = $this->getAction()) {
            $params['action'] = $tempParam;
        }
        
        return self::$_urlHelper->url($params,
                                      $this->getRoute(),
                                      $this->getResetParams());
    }
    
    /**
     * Sets action name for this page
     *
     * @param  string $action
     * @throws InvalidArgumentException  if invalid $action is given
     */
    public function setAction($action)
    {
        if (null !== $action && !is_string($action)) {
            $msg = '$action must be a string or null';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_action = $action;
    }
    
    /**
     * Returns action name for this page
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }
    
    /**
     * Sets controller name for this page
     *
     * @param  string|null $controller
     * @throws InvalidArgumentException  if invalid $controller is given
     */
    public function setController($controller)
    {
        if (null !== $controller && !is_string($controller)) {
            $msg = '$controller must be a string or null';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_controller = $controller;
    }
    
    /**
     * Returns controller name for this page
     *
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }
    
    /**
     * Sets module name for this page
     *
     * @param  string|null $module
     * @throws InvalidArgumentException  if invalid $module is given
     */
    public function setModule($module)
    {
        if (null !== $module && !is_string($module)) {
            $msg = '$module must be a string or null';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_module = $module;
    }
    
    /**
     * Returns module name for this page
     *
     * @return string
     */
    public function getModule()
    {
        return $this->_module;
    }
    
    /**
     * Sets params for this page
     * 
     * Params are used when assembling URL.
     *
     * @param array|null $params  [optional] if null is given, params will
     *                            be cleared
     * @return Zym_Navigation_Page_Abstract
     */
    public function setParams(array $params = null)
    {
        if (null === $params) {
            $this->_params = array();
        } else {
            // TODO: do this more intelligently?
            $this->_params = $params;
        }
        
        return $this;
    }
    
    /**
     * Returns params for this page
     * 
     * Params are used when assembling URL.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
    
    /**
     * Sets route name for this page
     *
     * @param  string $route
     * @throws InvalidArgumentException  if invalid $route is given
     */
    public function setRoute($route)
    {
        if (null !== $route && (!is_string($route) || strlen($route) < 1)) {
            $msg = '$route must be a non-empty string or null';
            throw new InvalidArgumentException($msg);
        }
        
        $this->_route = $route;
    }
    
    /**
     * Returns route name for this page
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->_route;
    }
    
    /**
     * Sets whether params should be reset when assembling URL
     *
     * @param bool $resetParams
     */
    public function setResetParams($resetParams)
    {
        $this->_resetParams = (bool)$resetParams;
    }
    
    /**
     * Returns whether params should be reset when assembling URL
     *
     * @return bool
     */
    public function getResetParams()
    {
        return $this->_resetParams;
    }
    
    /**
     * Sets action helper for assembling URLs
     *
     * @param Zend_Controller_Action_Helper_Url $uh
     */
    public static function setUrlHelper(Zend_Controller_Action_Helper_Url $uh)
    {
        self::$_urlHelper = $uh;
    }
    
    // Public methods:
    
    /**
     * Returns an array representation of the page
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            array(
                'action'       => $this->getAction(),
                'controller'   => $this->getController(),
                'module'       => $this->getModule(),
                'params'       => $this->getParams(),
                'route'        => $this->getRoute(),
                'reset_params' => $this->getResetParams()
            )); 
    }
}
