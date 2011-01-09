<?php
namespace services;
use Equ\Crud\Service;
use plugins\UserEntityBuilder;

/**
 * User service class
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class User extends Service {

  /**
   * Initialize builders and element creator factory
   */
  public function __construct() {
    $this->setEntityBuilder(new UserEntityBuilder($this->getEntityManager(), $this->getEntityClass()));
  }

  /**
   * @return string
   */
  public function getEntityClass() {
    return 'entities\User';
  }

  /**
   * @param string $email
   * @param string $password
   */
  public function login($email, $password) {
    try {
      $authAdapter = new \Equ\Auth\DoctrineAdapter(
        $this->getEntityManager()->getRepository('entities\User'),
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
}