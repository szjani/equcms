<?php
namespace entities;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Abstract Role class to handle roles (users, groups hierarchy)
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @gedmo:Tree(type="nested")
 * @Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @Table(name="`role`")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"user" = "User", "usergroup" = "UserGroup"})
 */
abstract class Role extends \Equ\Entity implements \Zend_Acl_Role_Interface {

  /**
   * @Column(name="id", type="integer")
   * @Id
   * @GeneratedValue
   * @var int
   */
  protected $id;

  /**
   * @gedmo:TreeParent
   * @ManyToOne(targetEntity="UserGroup", inversedBy="children")
   * @JoinColumn(name="parent_id", referencedColumnName="id", onDelete="cascade")
   * @var UserGroup
   */
  protected $parent;

  /**
   * @OneToMany(targetEntity="Role", mappedBy="parent")
   * @var Doctrine\Common\Collections\ArrayCollection
   */
  protected $children;

  /**
   * @gedmo:TreeLeft
   * @Column(name="lft", type="integer")
   */
  protected $lft;

  /**
   * @gedmo:TreeRight
   * @Column(name="rgt", type="integer")
   */
  protected $rgt;

  /**
   * @OneToMany(targetEntity="RoleResource", mappedBy="role")
   * @var ArrayCollection
   */
  protected $roleResources;

  /**
   * @gedmo:TreeLevel
   * @Column(name="lvl", type="integer")
   */
  protected $lvl;

  /**
   * @Column(name="role", type="string", length=255, nullable=false)
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

}