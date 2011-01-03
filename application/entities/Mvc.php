<?php
namespace entities;

/**
 * Mvc URL entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @Entity(repositoryClass="Gedmo\Tree\Repository\TreeNodeRepository")
 * @Table(name="`mvc`")
 */
class Mvc extends Resource {

  const SEPARATOR = '/';

  protected $defaultModule     = 'index';
  protected $defaultController = 'index';
  protected $defaultAction     = 'index';

  /**
   * @Column(name="module", type="string", length=84, nullable=true)
   * @var string
   */
  private $module;

  /**
   * @Column(name="controller", type="string", length=84, nullable=true)
   * @var string
   */
  private $controller;

  /**
   * @Column(name="action", type="string", length=84, nullable=true)
   * @var string
   */
  private $action;

  /**
   * @var \Zend_Navigation_Page_Mvc
   */
  private $navigationPage = null;

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
      $page = new \Zend_Navigation_Page_Mvc();
      $page
        ->setModule($this->module)
        ->setController($this->controller)
        ->setAction($this->action)
        ->setResource($this)
        ->setId($this->createPath('_'));
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

}