<?php
namespace modules\mvc\plugins;
use Equ\Entity\FormBuilder;

/**
 * FormBuilder to Mvc entities
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class MvcFormBuilder extends FormBuilder {

  /**
   * @return array
   */
  public function getIgnoredFields() {
    return array('lft', 'rgt', 'lvl', 'resource');
  }

}