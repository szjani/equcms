<?php
namespace Equ\Auth;

/**
 * Authenticates user with Doctrine repository
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class DoctrineAdapter implements \Zend_Auth_Adapter_Interface {

  /**
   * @var RepositoryInterface
   */
  private $repository;

  /**
   *
   * @var string
   */
  private $credential;

  /**
   * @var string
   */
  private $password;

  /**
   * @param RepositoryInterface $repo
   * @param string $credential
   * @param string $password
   */
  public function __construct(RepositoryInterface $repo, $credential, $password) {
    $this->repository = $repo;
    $this->credential = $credential;
    $this->password   = $password;
  }

  /**
   * @return \Zend_Auth_Result
   */
  public function authenticate() {
    $result = null;
    try {
      $user = $this->repository->authenticate($this->credential, $this->password);
      $result = new \Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
    } catch (Exception $e) {
      $result = new \Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null);
    }
    return $result;
  }

}