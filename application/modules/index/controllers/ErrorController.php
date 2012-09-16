<?php

use Equ\Controller\Exception\PermissionException;
use Equ\Message;

class ErrorController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch
            ->addActionContext('error', 'json')
            ->initContext();

        $this->_helper->autoTitle('An error occurred');
    }

    public function errorAction()
    {
        if ($this->getInvokeArg('errorview') && $this->getInvokeArg('errorview') != 'error') {
            $this->_helper->viewRenderer($this->getInvokeArg('errorview'));
            $this->_helper->layout->disableLayout();
        }

        $errors = $this->_getParam('error_handler');

        if ($errors->exception instanceof PermissionException) {
            $this->_helper->flashMessenger($errors->exception->getMessage(), Message::ERROR);
            if (!$this->_request->isXmlHttpRequest()) {
                $this->_helper->redirectHereAfterPost();
                $this->_helper->redirector->gotoRouteAndExit(
                    array('module' => 'user', 'controller' => 'index', 'action' => 'login'), 'defaultlang'
                );
            }
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found (404)';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        $this->view->exceptionMessage = $errors->exception->getMessage();
        $this->view->request = $errors->request;
    }

}

