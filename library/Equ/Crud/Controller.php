<?php
namespace Equ\Crud;
use Doctrine\ORM\EntityManager;
use Equ\Entity\FormBuilder;

/**
 * Controller of CRUD operations
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
abstract class Controller extends \Factory_Controller {

  /**
   * @var FormBuilder
   */
  private $mainFormBuilder = null;

  private $filterFormBuilder = null;

  protected $ignoredFields = array();

  /**
   * @var array
   */
  protected $cuForms = array();

  private $filterForm = null;

  public function getIgnoredFields() {
    return $this->ignoredFields;
  }

  /**
   * @return EntityManager
   */
  protected final function getEntityManager() {
    return $this->_helper->serviceContainer('doctrine.entitymanager');
  }

  /**
   * @return FormBuilder
   */
  public final function getMainFormBuilder() {
    if ($this->mainFormBuilder === null) {
      $this->mainFormBuilder = new FormBuilder($this->getEntityManager());
    }
    return $this->mainFormBuilder;
  }

  /**
   * @param FormBuilder $formBuilder
   * @return Service
   */
  public final function setMainFormBuilder(FormBuilder $formBuilder) {
    $this->mainFormBuilder = $formBuilder;
    return $this;
  }

  /**
   * @return FormBuilder
   */
  public final function getFilterFormBuilder() {
    if ($this->filterFormBuilder === null) {
      $this->filterFormBuilder = new FormBuilder($this->getEntityManager());
    }
    return $this->filterFormBuilder;
  }

  /**
   * @param FormBuilder $formBuilder
   * @return Service
   */
  public final function setFilterFormBuilder(FormBuilder $formBuilder) {
    $this->filterFormBuilder = $formBuilder;
    return $this;
  }

  /**
   * Retrieves an empty (or preinitialized) \Zend_Form object
   *
   * @return \Equ\Form
   */
  public function createEmptyForm() {
    return new \Equ\Form();
  }

  /**
   *
   * @param int $id
   * @param boolean $refresh
   * @return \Equ\Form
   */
  public function getCUForm($id = null, $refresh = false) {
    if (!\array_key_exists($id, $this->cuForms) || $refresh) {
      $entity      = $this->getService()->getEntity($id);
      $formBuilder = $this->getMainFormBuilder();
      $formBuilder->setForm($this->createEmptyForm());
      if (!($entity instanceof \Equ\Entity\Visitable)) {
        throw new Exception("Entity must implements '\Equ\Entity\Visitable' interface");
      }
      $entity->accept($formBuilder);
      $this->cuForms[$id] = $formBuilder->getForm();
    }
    return $this->cuForms[$id];
  }

    public function getFilterForm(array $values = array(), $refresh = false) {
    if ($this->filterForm === null || $refresh) {
//      $form = $this->getMainForm();
//      $filterForm = clone $form;
      $entity      = $this->getEntity();
      $formBuilder = $this->getFilterFormBuilder();
      $formBuilder
        ->createDefaultValidators(false)
        ->setForm($this->createEmptyForm());
      if (!($entity instanceof \Equ\Entity\Visitable)) {
        throw new Exception("Entity must implements '\Equ\Entity\Visitable' interface");
      }
      $entity->accept($formBuilder);
      $filterForm = $formBuilder->getForm();

      /* @var $filterForm \Zend_Form */
      $filterForm->setMethod(\Zend_Form::METHOD_GET);
      $filterForm->getElement('save')->setLabel('Filter');
      /* @var $element \Zend_Form_Element */
//      foreach ($filterForm as $element) {
//        $element->clearValidators();
//        $element->setRequired(false);
//      }
      $filterForm->setDefaults($values);
      $this->filterForm = $filterForm;
    }
    return $this->filterForm;
  }

  /**
   * Adds general CRUD script path
   */
  public function init() {
    parent::init();
    $this->view->addScriptPath(dirname(__FILE__) . '/views/scripts');
    $title = $this->view->pageTitle =
    	"Navigation/{$this->_getParam('module')}/{$this->_getParam('controller')}/{$this->_getParam('action')}/label";
    $this->view->headTitle($this->view->translate($title));
  }

  /**
   * @param int $id
   */
  protected function initHiddenNavigationItemWithId($id) {
    $navigation = $this->_helper->serviceContainer('navigation');
    $page = $navigation->findById(
      "{$this->_getParam('module')}_{$this->_getParam('controller')}_{$this->_getParam('action')}"
    );
    if ($page) {
      $page
        ->setParams(array('id' => $id))
        ->setVisible(true);
    }
  }

  /**
   * If .phtml is in application folder it renders that,
   * otherwise generic .phtml will be rendered
   *
   * @param string $script
   * @param string $name
   */
  public function renderScript($script, $name = null) {
    try {
      $this->render(substr($script, 0, -6), $name);
    } catch (\Zend_View_Exception $e) {
      parent::renderScript($script, $name);
    }
  }

  /**
   * Use Factory_Service_Container to instantiate service class!
   *
   * @return \Equ\Crud\Service
   */
  protected abstract function getService();

  public function indexAction() {
    $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
  }
  
  /**
   * Calls create method of service with form values
   */
  public function createAction() {
    $form = $this->getCUForm();
    $form->setDefaults($this->_request->getPost());
    try {
      if ($this->_request->isPost()) {
        if (!$form->isValid($this->_request->getPost())) {
          throw new \Exception('Invalid values in form');
        }
        $dtoBuilder = new \Equ\Form\DTOBuilder();
        $form->accept($dtoBuilder);
        $this->getService()->create($dtoBuilder->getDTO());
        $this->addMessage('Crud/Create/Success');
        $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
      }
      $this->view->createForm = $form;
    } catch (\Exception $e) {
        $this->addMessage('Crud/Create/UnSuccess', 'default', \Factory_Message::ERROR);
        $this->view->createForm = $form;
    }
    $this->renderScript('create.phtml');
  }

  /**
   * Calls update method of sevice with form values
   */
  public function updateAction() {
    $id   = $this->_getParam('id');
    $form = null;
    $this->initHiddenNavigationItemWithId($id);
    try {
      $form = $this->getCUForm($id);
      if ($this->_request->isPost()) {
        if (!$form->isValid($this->_request->getPost())) {
          throw new \Exception('Invalid values in form');
        }
        $dtoBuilder = new \Equ\Form\DTOBuilder();
        $form->accept($dtoBuilder);
        $this->getService()->update($id, $dtoBuilder->getDTO());
        $this->addMessage('Crud/Update/Success');
        $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
      }
      $this->view->updateForm = $form;
    } catch (\Exception $e) {
      $this->addMessage('Crud/Update/UnSuccess', 'default', $type = \Factory_Message::ERROR);
      $this->view->updateForm = $form;
    }
    $this->renderScript('update.phtml');
  }

  /**
   * Calls delete method of service
   */
  public function deleteAction() {
    $id = $this->_getParam('id');
    $this->initHiddenNavigationItemWithId($id);
    try {
      $this->getService()->delete($id);
      $this->addMessage('Crud/Delete/Success');
      $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
    } catch (Exception $e) {
      $this->addMessage('Crud/Delete/UnSuccess', 'default', $type = \Factory_Message::ERROR);
    }
    $this->renderScript('delete.phtml');
  }

  /**
   * Calls list method of service,
   * lists items with Zend_Paginator
   */
  public function listAction() {
//    $this->addMessage('Crud/Create/Success');
//    $this->addMessage('Crud/Create/UnSuccess', 'default', $type = \Factory_Message::ERROR);
//    $filterForm = $this->getService()->getFilterForm($this->_getAllParams());
    $filterForm = null;
    $this->view->paginator = $this->getService()->getPagePaginator(
      $this->_getParam('page', 1),
      $this->_getParam('items', 10),
      $this->_getParam('sort'),
      $this->_getParam('order', 'ASC')
//      $this->_getAllParams()
    );
    $this->view->keys        = \array_diff($this->getService()->getTableFieldNames(), $this->getIgnoredFields());
    $this->view->currentSort = $this->_getParam('sort');
    $this->view->nextOrder   = $this->_getParam('order', 'ASC') == 'ASC' ? 'DESC' : 'ASC';
    $this->view->filterForm  = $filterForm;
    $this->renderScript('list.phtml');
  }

}