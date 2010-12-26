<?php
namespace Entity;

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

  protected $defaultModule     = 'default';
  protected $defaultController = 'index';
  protected $defaultAction     = 'index';

  /**
   * @Column(name="id", type="integer")
   * @Id
   * @GeneratedValue
   * @var int
   */
  private $id;

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
   * @Column(name="url", type="string", length=255, unique=true)
   * @var string
   */
  private $url;

  private $navigationPage = null;

  protected function generateUrl() {
    $this->url = (string)$this->module;
    if (!empty($this->controller)) {
      $this->url .= self::SEPARATOR . $this->controller;
    }
    if (!empty($this->action)) {
      $this->url .= self::SEPARATOR . $this->action;
    }
  }

  public function getId() {
    return $this->id;
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
    if (empty($this->module)) {
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

  public function __toString() {
    return $this->getResourceId();
  }

  /**
   * @return string
   */
  public function getResourceId() {
    return $this->url;
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
        ->setLabel($this->url)
        ->setId(\str_replace(self::SEPARATOR, '_', $this->url))
        ->setTitle($this->url);
      $this->navigationPage = $page;
    }
    return $this->navigationPage;
  }

}