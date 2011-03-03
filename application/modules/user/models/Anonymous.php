<?php
namespace modules\user\models;
use entities\User;

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
class Anonymous extends User {

  const NAME = 'anonymous';

  public function __construct() {}

  public function getRoleId() {
    return self::NAME;
  }

}