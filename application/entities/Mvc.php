<?php
namespace entities;
use Equ\Navigation\Item as NavigationItem;

/**
 * Mvc URL entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @Entity(repositoryClass="entities\MvcRepository")
 * @Table(name="`mvc`")
 */
class Mvc extends Resource implements NavigationItem {

  const SEPARATOR = '/';

  protected $defaultModule     = 'index';
  protected $defaultController = 'index';
  protected $defaultAction     = 'index';

  /**
   * @Column(name="module", type="string", length=84, nullable=true)
   * @var string
   */
  protected $module;

  /**
   * @Column(name="controller", type="string", length=84, nullable=true)
   * @var string
   */
  protected $controller;

  /**
   * @Column(name="action", type="string", length=84, nullable=true)
   * @var string
   */
  protected $action;

  /**
   * @var \Zend_Navigation_Page_Mvc
   */
  private $navigationPage = null;
  
  public function __construct() {
    $this->generateUrl();
  }

  protected function generateUrl() {
    $resource = 'mvc:';
    $resource .= $this->createPath('.');
    $this->setResourceId($resource);
  }

  public function getModule() {
    return $this->module;
  }

  public function setModule($module) {
    $this->module = (string)$module;
    $this->generateUrl();
    return $this;
  }

  public function getController() {
    return $this->controller;
  }

  public function setController($controller) {
    if (!empty($controller) && empty($this->module)) {
      $this->setModule($this->defaultModule);
    }
    $this->controller = (string)$controller;
    $this->generateUrl();
    return $this;
  }

  public function getAction() {
    return $this->action;
  }

  public function setAction($action) {
    if (!empty($action) && empty($this->controller)) {
      $this->setController($this->defaultController);
    }
    $this->action = (string)$action;
    $this->generateUrl();
    return $this;
  }

  protected function createPath($separator = self::SEPARATOR) {
    $path = $this->getModule();
    if (!empty($this->controller)) {
      $path .= $separator . $this->controller;
    }
    if (!empty($this->action)) {
      $path .= $separator . $this->action;
    }
    return $path;
  }

  /**
   * @return \Zend_Navigation_Page_Mvc
   */
  public function getNavigationPage($refresh = false) {
    if ($this->navigationPage === null || $refresh) {
      $id = $this->createPath('_');
      $page = new \Zend_Navigation_Page_Mvc();
      $page
        ->setClass($this->createPath(' '))
        ->setModule($this->module)
        ->setController($this->controller)
        ->setAction($this->action)
        ->setResource($this->getResourceId())
        ->setPrivilege('list')
        ->setId($id !== '' ? $id : 'main');
      if ($this->getResourceId() == 'mvc:') {
        $page
          ->setLabel('Navigation' . self::SEPARATOR . 'main' . self::SEPARATOR . 'label')
          ->setTitle('Navigation' . self::SEPARATOR . 'main' . self::SEPARATOR . 'title');
      } else {
        $page
          ->setLabel('Navigation' . self::SEPARATOR . $this->createPath() . self::SEPARATOR . 'label')
          ->setTitle('Navigation' . self::SEPARATOR . $this->createPath() . self::SEPARATOR . 'title');
      }
      $this->navigationPage = $page;
    }
    return $this->navigationPage;
  }

  public function serialize() {
    $res = \unserialize(parent::serialize());
    $res['module'] = $this->module;
    $res['controller'] = $this->controller;
    $res['action'] = $this->action;
    return \serialize($res);
  }

  public function unserialize($serialized) {
    parent::unserialize($serialized);
    $serialized = \unserialize($serialized);
    $this->module = $serialized['module'];
    $this->controller = $serialized['controller'];
    $this->action = $serialized['action'];
  }


}