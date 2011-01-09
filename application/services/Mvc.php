<?php
namespace services;
use Equ\Crud\Service;

/**
 * Mvc service class to manage mvc records with CRUD methods
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Mvc extends Service {

  /**
   * @return string
   */
  public function getEntityClass() {
    return 'entities\Mvc';
  }

}