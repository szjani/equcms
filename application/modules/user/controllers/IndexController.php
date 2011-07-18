<?php
use
  modules\user\forms\Login,
  entities\User;

class User_IndexController extends \Zend_Controller_Action {

  public function loginAction() {
    $form = null;
    try {
      $formBuilder = $this->_helper->createFormBuilder(new Login(), User::className());
      $form = $formBuilder->getForm();
      if ( $this->_request->isPost()) {
        if (!$form->isValid($this->_request->getPost())) {
          throw new \Exception('Invalid login data');
        }
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->_helper->serviceContainer('doctrine.entitymanager');
        $user = $em->getRepository(User::className())->authenticate(
          $form->getValue('email'),
          $form->getValue('password')
        );
        Zend_Auth::getInstance()->getStorage()->write($user->getRoleId());
        $this->_helper->flashMessenger('Login success');
        $this->_helper->redirector->gotoRouteAndExit(array(), null, true);
      }
    } catch (\Exception $e) {
      $this->_helper->flashMessenger($e, Equ\Message::ERROR);
    }
    $this->view->loginForm = $form;
  }
  
  /**
   * Logout authenticated user
   */
  public function logoutAction() {
    try {
      Zend_Auth::getInstance()->clearIdentity();
      $this->_helper->flashMessenger('Logout success', Equ\Message::SUCCESS);
      $this->_helper->redirector->gotoRouteAndExit(array(), null, true);
    } catch (Exception $e) {
      $this->_helper->serviceContainer('log')->err($e);
      $this->_helper->flashMessenger('Logout unsuccess', Equ\Message::ERROR);
    }
  }

}