<?php
namespace services;
use Equ\Crud\Service;
use plugins\UserEntityBuilder;
use Equ\Auth\DoctrineAdapter;

/**
 * User service class
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    services
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class User extends Service {

  /**
   * Initialize builders and element creator factory
   */
  public function __construct($entityClass) {
    parent::__construct($entityClass);
    $this->setEntityBuilder(new UserEntityBuilder($this->getEntityManager(), $entityClass));
  }

  /**
   * Login user
   *
   * @param string $email
   * @param string $password
   */
  public function login($email, $password) {
    try {
      $authAdapter = new DoctrineAdapter(
        $this->getEntityManager()->getRepository($this->getEntityClass()),
        $email,
        $password
      );
      if (!Zend_Auth::getInstance()->authenticate($authAdapter)->isValid()) {
        throw new Exception("Unsuccess authentication. E-mail: '$email'");
      }
    } catch (Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }

  /**
   * Logout authenticated user
   */
  public function logout() {
    try {
      Zend_Auth::getInstance()->clearIdentity();
    } catch (Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }
}