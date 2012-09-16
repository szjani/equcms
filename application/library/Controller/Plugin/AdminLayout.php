<?php
namespace library\Controller\Plugin;

class AdminLayout extends \Zend_Controller_Plugin_Abstract
{
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        if ($request->getControllerName() == 'admin') {
            \Zend_Layout::getMvcInstance()->setLayout('admin-layout');
        }
    }

}