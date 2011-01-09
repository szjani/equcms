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
 * @Entity(repositoryClass="Gedmo\Tree\Repository\TreeNodeRepository")
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

  public function serialize() {
    $res = \unserialize(parent::serialize());
    $res['name']    = $this->getName();
    return \serialize($res);
  }

  public function unserialize($serialized) {
    parent::unserialize($serialized);
    $serialized = \unserialize($serialized);
    $this->name = $serialized['name'];
  }

}