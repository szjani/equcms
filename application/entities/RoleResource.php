<?php
namespace entities;

/**
 * JoinClass between Role and Resource entities
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @Entity
 * @Table(
 *   name="role_resource",
 *   uniqueConstraints={
 *     @UniqueConstraint(name="roleresource_role_resource_constraint", columns={"role_id", "resource_id"})
 *   }
 * )
 */
class RoleResource extends \Equ\Entity {

  /**
   * @Column(name="id", type="integer")
   * @Id
   * @GeneratedValue
   * @var int
   */
  private $id;

  /**
   * @ManyToOne(targetEntity="Role", inversedBy="roleResources")
   * @JoinColumn(name="role_id", referencedColumnName="id", nullable=false, onDelete="cascade")
   * @var Role
   */
  private $role;

  /**
   * @ManyToOne(targetEntity="Resource", inversedBy="roleResources")
   * @JoinColumn(name="resource_id", referencedColumnName="id", nullable=false, onDelete="cascade")
   * @var Resource
   */
  private $resource;

  /**
   * @Column(name="allowed", type="boolean")
   * @var boolean
   */
  private $allowed;

  /**
   * @Column(name="privilege", type="string", length=255, nullable=true)
   * @var string
   */
  private $privilege = null;

  public function getId() {
    return $this->id;
  }

  public function getRole() {
    return $this->role;
  }

  public function setRole(Role $role) {
    $this->role = $role;
    return $this;
  }

  public function getResource() {
    return $this->resource;
  }

  public function setResource(Resource $resource) {
    $this->resource = $resource;
    return $this;
  }

  public function getPrivilege() {
    return $this->privilege;
  }

  public function setPrivilege($privilege) {
    if (\strlen($privilege) == 0) {
      $privilege = null;
    }
    $this->privilege = $privilege;
    return $this;
  }

  public function isAllowed() {
    return $this->allowed;
  }

  /**
   * @param boolean $allow
   * @return RoleResource
   */
  public function setAllowed($allow = true) {
    $this->allowed = (boolean)$allow;
    return $this;
  }
  
  public function __toString() {
    return $this->getRole() . ' - ' . $this->getResource();
  }

}