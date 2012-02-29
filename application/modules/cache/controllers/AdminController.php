<?php
use Equ\Message;

class Cache_AdminController extends Zend_Controller_Action {
  
  /**
   * @var Doctrine\Common\Cache\AbstractCache
   */
  public $doctrineCache;
  
  /**
   * @var Zend_Cache_Core
   */
  public $cacheData;
  
  /**
   * @var Zend_Cache_Core
   */
  public $cachePage;
  
  /**
   * @var Zend_Cache_Core
   */
  public $cacheSystem;
  
  public function init() {
    parent::init();
    $title = $this->view->pageTitle =
      $this->view->translate(
        "Navigation/{$this->_getParam('module')}/{$this->_getParam('controller')}/{$this->_getParam('action')}/label"
      );
    $this->view->headTitle($title);
  }
  
  /**
   * Redirects to listAction
   */
  public function indexAction() {
    $this->_helper->redirector->gotoRouteAndExit(array('action' => 'list'));
  }
  
  public function listAction() {
    $this->view->keys = array('name', 'info', 'lifetime');
    $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array(array(
      array(
        'id' => 'doctrineCache',
        'name' => 'Doctrine cache',
        'lifetime' => '0',
        'info' => 'Annotation and query cache for ORM'
      ),
      array(
        'id' => 'cacheSystem',
        'name' => 'System cache',
        'lifetime' => $this->cacheSystem->getOption('lifetime'),
        'info' => 'Long lifetime cache for translation and localization, navigation, ACL'
      ),
      array(
        'id' => 'cacheData',
        'name' => 'Data cache',
        'lifetime' => $this->cacheData->getOption('lifetime'),
        'info' => 'Short lifetime cache for some other things'
      ),
      array(
        'id' => 'cachePage',
        'name' => 'Page cache',
        'lifetime' => $this->cachePage->getOption('lifetime'),
        'info' => 'Page cache to store whole pages'
      ),
    )));
    $this->view->paginator = $paginator;
  }
  
  public function purgeAction() {
    try {
      $cacheName = $this->_getParam('id');
      if (!array_key_exists($cacheName, get_object_vars($this))) {
        throw new \RuntimeException('Invalid cache id!');
      }
      $cache = $this->$cacheName;
      if ($cache instanceof Doctrine\Common\Cache\AbstractCache) {
        $cache->deleteAll();
      }
      if ($cache instanceof Zend_Cache_Core) {
        $cache->clean();
      }
      $this->_helper->flashMessenger('Crud/Purge/Success');
    } catch (Exception $e) {
      $this->_helper->flashMessenger('Crud/Purge/UnSuccess', Message::ERROR);
    }
    $this->_helper->redirector('list');
  }
  
}