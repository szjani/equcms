<?php
use modules\user\forms\Login;

class User_IndexController extends \Equ\Controller {

  public function loginAction() {
    throw new \Exception('teswt');
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
        $this->addMessage('Login success');
        $this->redirector->gotoRouteAndExit(array(), null, true);
      }
    } catch (\Exception $e) {
      $this->addMessage($e);
    }
    $this->view->loginForm = $form;
  }

}