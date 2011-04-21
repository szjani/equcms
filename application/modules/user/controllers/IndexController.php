<?php
use modules\user\forms\Login;

class User_IndexController extends \Zend_Controller_Action {

  public function loginAction() {
    $form = new Login();
    try {
      if ( $this->_request->isPost()) {
        if (!$form->isValid($this->_request->getPost())) {
          throw new \Exception('Invalid login data');
        }
        $this->_helper->serviceContainer('user')->login(
          $form->getValue('email'),
          $form->getValue('password')
        );
        $this->_helper->flashMessenger('Login success');
        $this->redirector->gotoRouteAndExit(array(), null, true);
      }
    } catch (\Exception $e) {
      $this->_helper->flashMessenger($e, Equ\Message::ERROR);
    }
    $this->view->loginForm = $form;
  }

}