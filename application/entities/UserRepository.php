<?php
namespace entities;
use
  Gedmo\Tree\Entity\Repository\NestedTreeRepository,
  Equ\Auth\AuthenticatedUserStorage,
  Equ\Auth\RepositoryInterface;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class UserRepository extends NestedTreeRepository implements RepositoryInterface, AuthenticatedUserStorage {

  /**
   * @var User
   */
  private $authenticatedUser;
  
  /**
   * @param string $credential
   * @param string $password
   * @throws Exception
   * @return User
   */
  public function authenticate($credential, $password) {
    $user = $this->findOneBy(array(
      'email' => $credential,
      'passwordHash' => User::generatePasswordHash($password)
    ));
    if ($user === null) {
      throw new Exception("Invalid user with credential '$credential'");
    }
    return $user;
  }
  
  /**
   * @return User
   */
  public function getAuthenticatedUser() {
    if (is_null($this->authenticatedUser)) {
      $user = \Zend_Auth::getInstance()->getIdentity();
      if (is_string($user)) {
        $user = $this->findOneBy(array('role' => $user));
        if (!$user) {
          throw new \RuntimeException('You have to be authenticated!');
        }
        $user->setLoggedIn();
      }
      $this->authenticatedUser = $user;
    }
    return $this->authenticatedUser;
  }
  
}