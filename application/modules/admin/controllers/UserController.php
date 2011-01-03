<?php
class Admin_UserController extends Equ\Crud\Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  protected function getService() {
    return $this->_helper->serviceContainer('user');
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