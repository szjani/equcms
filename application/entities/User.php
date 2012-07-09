<?php
namespace entities;
use
  Equ\ClassMetadata,
  Equ\Object\Validatable,
  Equ\Object\Validator,
  Equ\Auth\UserInterface,
  Doctrine\ORM\Mapping as ORM;

/**
 * User entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @ORM\Entity(repositoryClass="entities\UserRepository")
 * @ORM\Table(name="user", indexes={@ORM\Index(name="user_password_idx", columns={"password_hash"})})
 * @ORM\HasLifecycleCallbacks
 */
class User extends \Equ\Entity implements Validatable, UserInterface {
  
  const PASSWORD_SALT = '958rg!DdfJko$~tz)3/Tiq3rf9;a43gFT]A46DFaAeg;a43';
  
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue
   * @var int
   */
  protected $id;

  /**
   * @ORM\Column(name="email", type="string", unique=true, nullable=false)
   * @var string
   */
  protected $email;

  /**
   * @ORM\Column(name="password_hash", type="string", length=40, nullable=false)
   * @var string
   */
  protected $passwordHash;

  /**
   * @ORM\Column(name="activation_code", type="string", length=12)
   * @var string
   */
  protected $activationCode;
  
  /**
   * @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="users")
   * @ORM\JoinColumn(name="user_group_id", referencedColumnName="id", nullable=false, onDelete="cascade")
   * @var UserGroup
   */
  protected $userGroup;
  
  protected $isLoggedIn = false;

  /**
   * @param string $email
   * @param string $password
   */
  public function __construct($email, $password) {
    $this
      ->setEmail($email)
      ->setPassword($password);
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getPrincipal() {
    return $this->getEmail();
  }
  
  public static function loadValidators(Validator $validator) {
    $validator
      ->add('email', new \Zend_Validate_EmailAddress())
      ->add('password', new \Zend_Validate_NotEmpty())
      ->add('password', new \Zend_Validate_StringLength(5, 12));
  }
  
  /**
   * @ORM\PrePersist
   */
  public function initActivationCode() {
    $this->setActivationCode(self::generateString(12));
  }

  /**
   * Generates a random password
   *
   * @param int $length
   * @return string
   */
  public static function generateString($length = 8) {
    $length = (int) $length;
    if ($length < 0) {
      throw new Exception("Invalid password length '$length'");
    }
    $set = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = strlen($set);
    $ret = '';
    for ($i = 0; $i < $length; $i++) {
      $ret .= $set[rand(0, $num - 1)];
    }
    return $ret;
  }

  /**
   * Generates a password hash
   *
   * @param string $password
   * @return string
   */
  public static function generatePasswordHash($password) {
    return sha1($password . self::PASSWORD_SALT);
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param string $email
   * @return User
   */
  public function setEmail($email) {
    $this->email = $email;
    return $this;
  }

  /**
   * @return string
   */
  public function getPasswordHash() {
    return $this->passwordHash;
  }

  /**
   * @param string $password
   * @return User
   */
  public function setPassword($password) {
    $this->passwordHash = self::generatePasswordHash(trim($password));
    return $this;
  }

  /**
   * @return string
   */
  public function getActivationCode() {
    return $this->activationCode;
  }

  /**
   * @param string $activationCode
   * @return User
   */
  public function setActivationCode($activationCode) {
    $this->activationCode = $activationCode;
    return $this;
  }
  
  /**
   * @return boolean
   */
  public function isLoggedIn() {
    return $this->isLoggedIn;
  }

  /**
   * @param boolean $loggedIn
   * @return User 
   */
  public function setLoggedIn($loggedIn = true) {
    $this->isLoggedIn = (boolean)$loggedIn;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getRoleId() {
    return $this->getUserGroup()->getRoleId();
  }

  /**
   * @return UserGroup
   */
  public function getUserGroup() {
    return $this->userGroup;
  }
  
  /**
   * @param UserGroup $group
   * @return User 
   */
  public function setUserGroup(UserGroup $group) {
    $this->userGroup = $group;
    return $this;
  }
  
  public function __toString() {
    return $this->email;
  }
  
  /**
   * @return array
   */
  public function toArray() {
    return array(
      'id' => $this->getId(),
      'roleId' => $this->getRoleId(),
      'email' => $this->getEmail()
    );
  }
  
}