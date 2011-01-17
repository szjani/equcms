<?php
namespace Equ\Crud;
use Doctrine\ORM\EntityManager;
use Equ\Entity\FormBuilder;
use Equ\Controller\Request\FilterDTOBuilder;
use Equ\Message;

/**
 * Controller of CRUD operations
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
abstract class Controller extends \Equ\Controller {

  /**
   * @var FormBuilder
   */
  private $mainFormBuilder = null;

  /**
   * @var FormBuilder
   */
  private $filterFormBuilder = null;

  /**
   * @var array
   */
  protected $ignoredFields = array();

  /**
   * @var boolean
   */
  protected $useFilterForm = true;

  /**
   * @var array
   */
  protected $cuForms = array();

  /**
   * @var \Zend_Form
   */
  private $filterForm = null;

  /**
   * @var \Equ\Crud\IService
   */
  private $crudService = null;

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

  protected abstract function getEntityClass();

  /**
   * @return \Equ\Crud\IService
   */
  protected function getCrudService() {
    if ($this->crudService === null) {
      $this->crudService = new Service($this->getEntityClass());
    }
    return $this->crudService;
  }

  /**
   * @param IService $service
   * @return Controller
   */
  protected function setCrudService(IService $service) {
    $this->crudService = $service;
    return $this;
  }

  /**
   * @return array
   */
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
   * Creates a form to create or update entity
   *
   * @param int $id
   * @param boolean $refresh
   * @return \Equ\Form
   */
  public function getCUForm($id = null, $refresh = false) {
    if (!\array_key_exists($id, $this->cuForms) || $refresh) {
      $entity = $this->getCrudService()->getEntity($id);
      if (!($entity instanceof \Equ\Entity\Visitable)) {
        throw new Exception("Entity must implements '\Equ\Entity\Visitable' interface");
      }
      $formBuilder = $this->getMainFormBuilder();
      $formBuilder->setForm($this->createEmptyForm());
      $entity->accept($formBuilder);
      $this->cuForms[$id] = $formBuilder->getForm();
    }
    return $this->cuForms[$id];
  }

  /**
   * Creates a form to filterable lists
   *
   * @param boolean $refresh
   * @return \Zend_Form
   */
  public function getFilterForm($refresh = false) {
    if ($this->filterForm === null || $refresh) {
      $entity      = $this->getCrudService()->getEntity();
      if (!($entity instanceof \Equ\Entity\Visitable)) {
        throw new Exception("Entity must implements '\Equ\Entity\Visitable' interface");
      }
      $formBuilder = $this->getFilterFormBuilder();
      $formBuilder
        ->disableForeignElements()
        ->createDefaultValidators(false)
        ->setForm($this->createEmptyForm())
        ->getElementCreatorFactory()->usePlaceHolders();
      $entity->accept($formBuilder);
      $this->filterForm = $formBuilder->getForm();
      $this->filterForm->setMethod(\Zend_Form::METHOD_GET);
      $this->filterForm->getElement('save')->setLabel('Crud/Filter');
      $router = $this->getFrontController()->getRouter();
      $this->filterForm->setAction($router->assemble($this->filterForm->getValues()));
    }
    return $this->filterForm;
  }

  /**
   * Enables visibility of hidden navigation items and set id parameter.
   * Useful for update action to show update nav. item.
   *
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
   * Redirects to listAction
   */
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
        $this->getCrudService()->create($dtoBuilder->getDTO());
        $this->addMessage('Crud/Create/Success');
        $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
      }
      $this->view->createForm = $form;
    } catch (\Exception $e) {
        $this->addMessage('Crud/Create/UnSuccess', 'default', Message::ERROR);
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
        $this->getCrudService()->update($id, $dtoBuilder->getDTO());
        $this->addMessage('Crud/Update/Success');
        $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
      }
      $this->view->updateForm = $form;
    } catch (\Exception $e) {
      $this->addMessage('Crud/Update/UnSuccess', 'default', Message::ERROR);
      $this->view->updateForm = $form;
    }
    $this->renderScript('update.phtml');
  }

  /**
   * Calls delete method of service
   */
  public function deleteAction() {
    $id = $this->_getParam('id');
    try {
      $this->getCrudService()->delete($id);
      $this->addMessage('Crud/Delete/Success');
      $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
    } catch (Exception $e) {
      $this->addMessage('Crud/Delete/UnSuccess', 'default', Message::ERROR);
    }
    $this->renderScript('delete.phtml');
  }

  /**
   * Calls list method of service,
   * lists items with Zend_Paginator
   */
  public function listAction() {
    $filterBuilder = new FilterDTOBuilder();
    $filterBuilder->visitRequest($this->_request);

    $this->view->paginator = $this->getCrudService()->getPagePaginator(
      $this->_getParam('page', 1),
      $this->_getParam('items', 10),
      $this->_getParam('sort'),
      $this->_getParam('order', 'ASC'),
      $filterBuilder->getDTO()
    );
    $this->view->keys        = \array_diff($this->getCrudService()->getTableFieldNames(), $this->getIgnoredFields());
    $this->view->currentSort = $this->_getParam('sort');
    $this->view->nextOrder   = $this->_getParam('order', 'ASC') == 'ASC' ? 'DESC' : 'ASC';
    if ($this->useFilterForm) {
      $filterForm = $this->getFilterForm();
      $filterForm->setDefaults($this->_getAllParams());
      $this->view->filterForm  = $filterForm;
    }
    $this->renderScript('list.phtml');
  }

}