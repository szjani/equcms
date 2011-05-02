<?php
namespace entities;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Equ\Auth\RepositoryInterface;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class UserRepository extends NestedTreeRepository implements RepositoryInterface {

  /**
   * @param string $credential
   * @param string $password
   * @throws Exception
   * @return User
   */
  public function authenticate($credential, $password) {
    $user = $this->findBy(array(
      'email' => $credential,
      'password' => User::generatePasswordHash($password)
    ));
    if ($user === null) {
      throw new Exception("Invalid user with credential '$credential'");
    }
    return $user;
  }
  
}