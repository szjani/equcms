<?php
use
  Equ\Crud\AbstractController,
  modules\user\plugins\UserFormBuilder,
  modules\user\plugins\UserFilterFormBuilder;

class User_AdminController extends AbstractController {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

  public function init() {
    parent::init();
    $elementCreator    = $this->_helper->serviceContainer('form.elementcreator.factory');
    $formBuilder       = new UserFormBuilder($this->getEntityManager(), $elementCreator);
    $filterFormBuilder = new UserFilterFormBuilder($this->getEntityManager(), $elementCreator);
    $this
      ->setMainFormBuilder($formBuilder)
      ->setFilterFormBuilder($filterFormBuilder);

    $formBuilder->setIgnoredFields(array('lft', 'rgt', 'lvl', 'passwordHash', 'activationCode', 'role'));
    $filterFormBuilder->setIgnoredFields(array('lft', 'rgt', 'lvl', 'passwordHash', 'role'));
    $this->setCrudService(new \services\User($this->getEntityClass()));
  }

  protected function getEntityClass() {
    return 'entities\User';
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