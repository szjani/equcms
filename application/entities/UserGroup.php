<?php
namespace entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Group entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="user_group")
 */
class UserGroup extends Role {

  /**
   * @ORM\Column(name="name", type="string", length=255)
   * @var string
   */
  protected $name;
  
  /**
   * @ORM\OneToMany(targetEntity="User", mappedBy="group")
   * @var ArrayCollection
   */
  protected $users;

  public function __construct($name) {
    parent::__construct();
    $this->setName($name);
    $this->users = new ArrayCollection();
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