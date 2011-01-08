<?php
namespace Equ\Auth;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
interface RepositoryInterface {

  /**
   * @throws Exception
   * @return mixed
   */
  public function authenticate($credential, $password);
  
}
