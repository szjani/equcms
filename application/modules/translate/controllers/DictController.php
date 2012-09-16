<?php

class Translate_DictController extends Zend_Controller_Action
{

    /**
     * @var Zend_Translate
     */
    public $translate;

    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $this->_response->setHeader('Content-type', 'application/json');
        echo Zend_Json::encode($this->translate->getAdapter()->getMessages());
    }

}