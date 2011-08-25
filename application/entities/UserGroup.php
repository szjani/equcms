<?php
namespace entities;

/**
 * Group entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @Table(name="`user_group`")
 */
class UserGroup extends Role {

  /**
   * @Column(name="name", type="string", length=255)
   * @var string
   */
  protected $name;

  public function __construct($name) {
    parent::__construct();
    $this->setName($name);
  }

  /**
   * @return string
   */
  public function getRoleId() {
    return $this->name;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
    $this->setRoleId($name);
    return $this;
  }

}