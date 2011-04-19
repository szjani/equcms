<?php
namespace modules\user\plugins;
use Equ\Entity\FormBuilder;

/**
 * User filter form builder
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    modules
 * @package     user
 * @subpackage  plugins
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class UserFilterFormBuilder extends FormBuilder {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'passwordHash', 'role');

  /**
   * Removes initialized activation code value
   */
  public function postVisit() {
    $this->getForm()->getElement('f_activationCode')->setValue('');
  }

}