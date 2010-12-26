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
 * @subpackage Action
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_Controller_Action_Abstract
 */
require_once 'Zym/Controller/Action/Abstract.php';

/**
 * @see Zym_Controller_Plugin_ErrorHandler
 */
require_once 'Zym/Controller/Plugin/ErrorHandler.php';

/**
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
abstract class Zym_Controller_Action_Error extends Zym_Controller_Action_Abstract
{
    /**
     * Error handler action
     *
     */
    const ACTION     = 'action';

    /**
     * Error handler controller
     *
     */
    const CONTROLLER = 'controller';

    /**
     * Error handler module
     *
     */
    const MODULE     = 'module';

    /**
     * Error handler params
     *
     */
    const PARAMS     = 'params';

    /**
     * Exception Object
     *
     * $_error->type (Zend_Controller_Plugin_ErrorHandler constants)
     * $_error->exception (Exception object)
     *
     * @var Zym_Controller_Plugin_ErrorHandler_Data
     */
    private $_error;

    /**
     * Error handler map
     *
     * @var array
     */
    private $_errorHandlers = array();

    /**
     * Fall back map
     *
     * @var array
     */
    private $_fallBack = array();

    /**
     * No fall back flag
     *
     * @var boolean
     */
    private $_noFallBack;

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
        // Set error
        $error = $request->getParam(Zym_Controller_Plugin_ErrorHandler::ERROR_PARAM);
        if ($error instanceof Zym_Controller_Plugin_ErrorHandler_Data) {
            $this->setError($error);
        }

        // Setup default fallback
        $defaultModule = $this->getFrontController()->getDefaultModule();
        $this->setFallBack('error', 'error', $defaultModule);

        // Error Handling map
        $this->setErrorHandlers(array(
            Zym_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER => array(
                self::ACTION     => 'not-found',
                self::CONTROLLER => 'error',
                self::MODULE     => $defaultModule
            ),
            Zym_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION     => array(
                self::ACTION     => 'not-found',
                self::CONTROLLER => 'error',
                self::MODULE     => $defaultModule
            ),

            Zym_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER         => array(
                self::ACTION     => 'internal',
                self::CONTROLLER => 'error',
                self::MODULE     => $defaultModule
            )
        ));

        // Call Parent
        parent::__construct($request, $response, $invokeArgs);
    }

    /**
     * Error handler
     *
     * This is the entrance to this controller used by the ErrorHandler
     * controller plugin (@see Zend_Controller_ErrorHandler)
     *
     * This action cannot be called directly, if someone does, it will
     * show up as a 404 notFound
     *
     * The magic of this controller happens in this action. Make sure that
     * the ErrorHandler is set to forward to this action.
     *
     * @return void
     */
    public function errorAction()
    {
        $error = $this->getError();
        if (!$error instanceof Zym_Controller_Plugin_ErrorHandler_Data) {
            // Reserve this action only for the ErrorHandler plugin
            require_once 'Zym/Controller/Action/Exception.php';
            throw new Zym_Controller_Action_Exception(
                'This action was called directly or Zym_Controller_Plugin_ErrorHandler was not used'
            );
        }

        $type              = $error->getType();
        $errorHandlers     = $this->getErrorHandlers();
        $currentModule     = $this->getRequest()->getModuleName();
        $currentController = $this->getRequest()->getControllerName();
        $fallBack          = $this->getFallBack();

        // Prevent looping to the same place
        if (strcasecmp($fallBack[self::MODULE], $currentModule) === 0 || $fallBack[self::MODULE] === null) {
            $isValidFall = !(strcasecmp($fallBack[self::CONTROLLER], $currentController) === 0
                                && $fallBack[self::ACTION] == 'error');
        } else {
            $isValidFall = true;
        }

        if (isset($errorHandlers[$type])) {
            call_user_func_array(array($this, '_forward'), $errorHandlers[$type]);
        } else if (!$this->isNoFallBack() && $isValidFall) {
            call_user_func_array(array($this, '_forward'), $fallBack);
        } else if (!$this->isNoFallBack() && !$isValidFall) {
            /**
             * @see Zym_Controller_Action_Exception
             */
            require_once 'Zym/Controller/Action/Exception.php';
            throw new Zym_Controller_Action_Exception(
                sprintf('An exception of type "%s" occurred and was not handled by "%s" '
                         . 'because falling back would of caused a loop', $type, get_class($this)));
        } else {
            /**
             * @see Zym_Controller_Action_Exception
             */
            require_once 'Zym/Controller/Action/Exception.php';
            throw new Zym_Controller_Action_Exception(
                sprintf('An exception of type "%s" occurred and was not handled by "%s"', $type, get_class($this)));
        }

        if (!$this->getInvokeArg('noViewRenderer') && $this->_helper->hasHelper('ViewRenderer')) {
            // Disable ViewRenderer
            $this->getHelper('ViewRenderer')->setNoRender();
        }

        // Clear header/body
        $this->getResponse()->clearHeaders()
                            ->clearBody();
    }

    /**
     * Set error
     *
     * @param Zym_Controller_Plugin_ErrorHandler_Data $error
     * @return Zym_Controller_Action_Error
     */
    public function setError(Zym_Controller_Plugin_ErrorHandler_Data $error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * Get error obj
     *
     * @return Zym_Controller_Plugin_ErrorHandler_Data
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * Clear and set error handlers
     *
     * @param array $array
     * @return Zym_Controller_Action_Error
     */
    public function setErrorHandlers(array $array = array())
    {
        // Clear handlers
        $this->_errorHandlers = array();

        // Add them from array
        foreach ($array as $type => $options) {
            $action     = is_array($options) && isset($options[self::ACTION])
                            ? $options[self::ACTION] : (is_string($options) ? $options : null);

            $controller = is_array($options) && isset($options[self::CONTROLLER])
                            ? $options[self::CONTROLLER] : null;

            $module     = is_array($options) && isset($options[self::MODULE])
                            ? $options[self::MODULE] : null;

            $params     = is_array($options) && isset($options[self::PARAMS])
                            ? $options[self::PARAMS] : array();

            $this->addErrorHandler($type, $action, $controller, $module, $params);
        }

        return $this;
    }

    /**
     * Add error handlers
     *
     * Error handlers are added in FIFO order
     *
     * @param string $type
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @return Zym_Controller_Action_Error
     */
    public function addErrorHandler($type, $action, $controller = null, $module = null, array $params = array())
    {
        $this->_errorHandlers[$type] = array(
            self::ACTION     => $action,
            self::CONTROLLER => $controller,
            self::MODULE     => $module,
            self::PARAMS     => $params
        );

        return $this;
    }

    /**
     * Get array of error handlers
     *
     * @return array
     */
    public function getErrorHandlers()
    {
        return $this->_errorHandlers;
    }

    /**
     * Set to prevent fall back
     *
     * @param boolean $fall
     *
     * @return Zym_Controller_Action_Error
     */
    public function setNoFallBack($fall = true)
    {
        $this->_noFallBack = (bool) $fall;
        return $this;
    }

    /**
     * Get no fall back flag
     *
     * @return boolean
     */
    public function isNoFallBack()
    {
        return (bool) $this->_noFallBack;
    }

    /**
     * Set fall back action
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array  $params
     *
     * @return Zym_Controller_Action_Error
     */
    public function setFallBack($action, $controller = null, $module = null, array $params = array())
    {
        $this->_fallBack = array(
            self::ACTION     => $action,
            self::CONTROLLER => $controller,
            self::MODULE     => $module,
            self::PARAMS     => $params
        );

        return $this;
    }

    /**
     * Get fallback
     *
     * @return array
     */
    public function getFallBack()
    {
        return $this->_fallBack;
    }
}