<?php
use Equ\Crud\Controller;
use modules\mvc\plugins\MvcFormBuilder;
use Equ\Entity\ElementCreator\Dojo;

/**
 * Mvc controller. You can manage module/controller/action records.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Mvc_AdminController extends Controller {

  /**
   * @var array
   */
  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'resource');

  public function init() {
    parent::init();
    $mainFormBuilder = new MvcFormBuilder($this->getEntityManager());
    $mainFormBuilder->setElementCreatorFactory(new Dojo\Factory());
    $this
      ->setMainFormBuilder($mainFormBuilder)
      ->setFilterFormBuilder($mainFormBuilder);
  }

  protected function getEntityClass() {
    return 'entities\Mvc';
  }

}