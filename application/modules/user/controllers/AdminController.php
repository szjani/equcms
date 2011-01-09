<?php
use Equ\Crud\Controller;
use modules\user\plugins\UserFormBuilder;
use modules\user\plugins\UserFilterFormBuilder;
use Equ\Entity\ElementCreator\Dojo\Factory;

class User_AdminController extends Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  public function init() {
    parent::init();
    $formBuilder = new UserFormBuilder($this->getEntityManager());
    $formBuilder->setElementCreatorFactory(new Factory());
    $filterFormBuilder = new UserFilterFormBuilder($this->getEntityManager());
    $filterFormBuilder->setElementCreatorFactory(new Factory());
    $this
      ->setMainFormBuilder($formBuilder)
      ->setFilterFormBuilder($filterFormBuilder);
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

}