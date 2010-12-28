<?php
namespace Equ\Crud;

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
   * Adds general CRUD script path
   */
  public function init() {
    parent::init();
    $this->view->addScriptPath(dirname(__FILE__) . '/views/scripts');
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
    $this->_forward('list');
  }

  /**
   * Calls create method of service with form values
   */
  public function createAction() {
    $form = $this->getService()->getMainForm();
    $form->setDefaults($this->_request->getPost());
    try {
      if ($this->_request->isPost()) {
        $this->getService()->create($this->_request->getPost());
        $this->addMessage('Crud/Create/Success');
        $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
      }
      $this->view->createForm = $form;
    } catch (\Exception $e) {
        $this->addMessage('Crud/Create/UnSuccess');
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
    try {
      $form = $this->getService()->getMainForm($id);
      if ($this->_request->isPost()) {
        $this->getService()->update($id, $this->_request->getPost());
        $this->addMessage('Crud/Update/Success');
        $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
      }
      $this->view->updateForm = $form;
    } catch (\Exception $e) {
      $this->addMessage('Crud/Update/UnSuccess');
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
      $this->getService()->delete($id);
      $this->addMessage('Crud/Delete/Success');
      $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
    } catch (Exception $e) {
      $this->addMessage('Crud/Delete/UnSuccess');
    }
    $this->renderScript('delete.phtml');
  }

  /**
   * Calls list method of service,
   * lists items with Zend_Paginator
   */
  public function listAction() {
    $filterForm = $this->getService()->getFilterForm($this->_getAllParams());
    $this->view->paginator = $this->getService()->getPagePaginator(
      $this->_getParam('page', 1),
      $this->_getParam('items', 10),
      $this->_getParam('sort'),
      $this->_getParam('order', 'ASC')
//      $this->_getAllParams()
    );
    $this->view->keys        = $this->getService()->getTableFieldNames();
    $this->view->currentSort = $this->_getParam('sort');
    $this->view->nextOrder   = $this->_getParam('order', 'ASC') == 'ASC' ? 'DESC' : 'ASC';
    $this->view->filterForm  = $filterForm;
    $this->renderScript('list.phtml');
  }

}