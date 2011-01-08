<?php
use Equ\Crud\Controller;
use plugins\UserFormBuilder;

class User_AdminController extends Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  public function init() {
    parent::init();
    $formBuilder = new UserFormBuilder($this->getEntityManager());
    $formBuilder->setElementCreatorFactory(new \Equ\Entity\ElementCreator\Dojo\Factory());
    $this->setMainFormBuilder($formBuilder);
  }

  protected function getService() {
    return $this->_helper->serviceContainer('user');
  }

  /**
   * @param int $id
   * @param boolean $refresh
   * @return \Zend_Form
   */
  public function getCUForm($id = null, $refresh = false) {
    $form = parent::getCUForm($id, $refresh);
    if ($id !== null) {
      $form->removeElement('password');
    }
    return $form;
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