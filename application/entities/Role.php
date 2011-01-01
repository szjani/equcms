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
 * @Entity(repositoryClass="Gedmo\Tree\Repository\TreeNodeRepository")
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
  private $id;

  /**
   * @gedmo:TreeParent
   * @ManyToOne(targetEntity="UserGroup", inversedBy="children")
   * @var UserGroup
   */
  private $parent;

  /**
   * @OneToMany(targetEntity="Role", mappedBy="parent")
   * @var Doctrine\Common\Collections\ArrayCollection
   */
  protected $children;

  /**
   * @gedmo:TreeLeft
   * @Column(name="lft", type="integer")
   */
  private $lft;

  /**
   * @gedmo:TreeRight
   * @Column(name="rgt", type="integer")
   */
  private $rgt;

  /**
   * @OneToMany(targetEntity="RoleResource", mappedBy="role")
   * @var ArrayCollection
   */
  private $roleResources;

  /**
   * @gedmo:TreeLevel
   * @Column(name="lvl", type="integer")
   */
  private $lvl;

  public function __construct() {
    parent::__construct();
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
  public function setParent(UserGroup $parent) {
    $this->parent = $parent;
    return $this;
  }

  /**
   * @return ArrayCollection
   */
  public function getRoleResources() {
    return $this->roleResources;
  }

  public function __toString() {
    return $this->getRoleId();
  }

  public function getId() {
    return $this->id;
  }
}