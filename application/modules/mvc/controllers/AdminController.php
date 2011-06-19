<?php
use Equ\Crud\AbstractController;
use
  modules\mvc\forms\Create as CreateForm,
  modules\mvc\forms\Filter as FilterForm;

/**
 * Mvc controller. You can manage module/controller/action records.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Mvc_AdminController extends AbstractController {

  /**
   * @var array
   */
  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'resource');

  public function getFilterForm() {
    return new FilterForm();
  }

  public function getMainForm() {
    return new CreateForm();
  }
}