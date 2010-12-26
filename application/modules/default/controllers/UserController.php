<?php
class UserController extends Equ\Crud\Controller {

  protected function getService() {
    return new Default_Service_User();
  }

  public function loginAction() {
    $form = $this->getService()->getLoginForm();
    if ( $this->_request->isPost()) {
      try {
        $this->getService()->login($this->_request->getPost());
        $this->addMessage('Login success');
        $this->redirector->gotoRouteAndExit(array('module' => 'admin', 'controller' => 'blog-entry'), null, true);
      } catch (Factory_Service_Exception $e) {
        $this->addMessage($e);
      } catch (Factory_LoginException $e) {
        $this->addMessage($e);
      }
    }
    $this->view->loginForm = $form;
  }

}