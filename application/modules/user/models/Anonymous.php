<?php
namespace modules\user\models;
use Equ\Auth\UserInterface;

/**
 * Unauthenticated user (null object pattern)
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    modules
 * @package     user
 * @subpackage  models
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Anonymous implements UserInterface {

  const NAME = 'anonymous';

  public function getRoleId() {
    return self::NAME;
  }
  
  public function isLoggedIn() {
    return false;
  }
  
  public function toArray() {
    return array(
      'roleId' => $this->getRoleId()
    );
  }
  
  public function __toString() {
    return $this->getRoleId();
  }

}