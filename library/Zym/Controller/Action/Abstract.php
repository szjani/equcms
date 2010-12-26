<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
abstract class Zym_Controller_Action_Abstract extends Zend_Controller_Action
{
    /**
     * Class constructor
     *
     * The request and response objects should be registered with the
     * controller, as should be any additional optional arguments; these will be
     * available via {@link getRequest()}, {@link getResponse()}, and
     * {@link getInvokeArgs()}, respectively.
     *
     * When overriding the constructor, please consider this usage as a best
     * practice and ensure that each is registered appropriately; the easiest
     * way to do so is to simply call parent::__construct($request, $response,
     * $invokeArgs).
     *
     * After the request, response, and invokeArgs are set, the
     * {@link $_helper helper broker} is initialized.
     *
     * Finally, {@link init()} is called as the final action of
     * instantiation, and may be safely overridden to perform initialization
     * tasks; as a general rule, override {@link init()} instead of the
     * constructor to customize an action controller's instantiation.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs Any additional invocation arguments
     * @return void
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);

        if (isset($this->ajaxable)) {
            // Init AjaxContexts
            $ajaxContext = $this->getHelper('AjaxContext');
            $ajaxContext->initContext();
        }

        if (isset($this->contexts)) {
            // TODO: TEMPORARY FIX FOR ZF-3690
            if (!isset($ajaxContext) || (isset($ajaxContext) && $ajaxContext->getCurrentContext() === null)) {
                // Init ContextSwitch
                $this->getHelper('ContextSwitch')->initContext();
            }
        }
    }

    /**
     * Get the view object
     *
     * @return Zend_View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set view object
     *
     * @param Zend_View_Interface $view
     * @return Zym_Controller_Action_Abstract
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Get the view script suffix
     *
     * @return string
     */
    public function getViewSuffix()
    {
        return $this->viewSuffix;
    }

    /**
     * Set the view script suffix
     *
     * @param string $suffix
     * @return Zym_Controller_Action_Abstract
     */
    public function setViewSuffix($suffix)
    {
        $this->viewSuffix = $suffix;
        return $this;
    }

    /**
     * Perform a redirect to an action/controller/module with params.
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @param boolean $exit
     * @return void
     */
    protected function _goto($action, $controller = null, $module = null, array $params = array(), $exit = true)
    {
        if ($exit) {
            $this->_helper->redirector->gotoAndExit($action, $controller, $module, $params);
        } else {
            $this->_helper->redirector->goto($action, $controller, $module, $params);
        }
    }
}