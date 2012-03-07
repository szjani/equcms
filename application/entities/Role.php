<?php
namespace entities;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Abstract Role class to handle roles (users, groups hierarchy)
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="entities\RoleRepository")
 * @ORM\Table(name="`role`", indexes={
 *   @ORM\Index(name="role_lft_idx", columns={"lft"}),
 *   @ORM\Index(name="role_rgt_idx", columns={"rgt"}),
 *   @ORM\Index(name="role_lvl_idx", columns={"lvl"})
 * })
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "usergroup" = "UserGroup"})
 */
abstract class Role extends \Equ\Entity implements \Zend_Acl_Role_Interface {

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue
   * @var int
   */
  protected $id;

  /**
   * @Gedmo\TreeParent
   * @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="children")
   * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="cascade")
   * @var UserGroup
   */
  protected $parent;

  /**
   * @ORM\OneToMany(targetEntity="Role", mappedBy="parent")
   * @var Doctrine\Common\Collections\ArrayCollection
   */
  protected $children;

  /**
   * @Gedmo\TreeLeft
   * @ORM\Column(name="lft", type="integer")
   */
  protected $lft;

  /**
   * @Gedmo\TreeRight
   * @ORM\Column(name="rgt", type="integer")
   */
  protected $rgt;

  /**
   * @ORM\OneToMany(targetEntity="RoleResource", mappedBy="role")
   * @var ArrayCollection
   */
  protected $roleResources;

  /**
   * @Gedmo\TreeLevel
   * @ORM\Column(name="lvl", type="integer")
   */
  protected $lvl;

  /**
   * @ORM\Column(name="role", type="string", length=255, nullable=false, unique=true)
   * @var string
   */
  protected $role;

  public function __construct() {
    $this->roleResources = new ArrayCollection();
    $this->children      = new ArrayCollection();
  }

  /**
   * @return UserGroup
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * @param UserGroup $parent
   * @return Role
   */
  public function setParent(UserGroup $parent = null) {
    $this->parent = $parent;
    return $this;
  }

  /**
   * @return ArrayCollection
   */
  public function getRoleResources() {
    return $this->roleResources;
  }

  public function getRoleId() {
    return $this->role;
  }

  protected function setRoleId($roleId) {
    $this->role = (string)$roleId;
    return $this;
  }

  public function __toString() {
    return $this->getRoleId();
  }

  public function getId() {
    return $this->id;
  }
  
  public static function getDisplayField() {
    return 'role';
  }

}