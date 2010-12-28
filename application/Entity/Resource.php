<?php
namespace Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Resource entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @Entity(repositoryClass="Gedmo\Tree\Repository\TreeNodeRepository")
 * @Table(name="`resource`")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"mvc" = "Mvc"})
 */
abstract class Resource extends \Equ\Entity implements \Zend_Acl_Resource_Interface {

  /**
   * @Column(name="id", type="integer")
   * @Id
   * @GeneratedValue
   * @var int
   */
  private $id;

  /**
   * @gedmo:TreeParent
   * @ManyToOne(targetEntity="Resource", inversedBy="children")
   * @var Resource
   */
  private $parent;

  /**
   * @OneToMany(targetEntity="Resource", mappedBy="parent")
   * @var Doctrine\Common\Collections\ArrayCollection
   */
  private $children;

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
   * @gedmo:TreeLevel
   * @Column(name="lvl", type="integer")
   */
  private $lvl;
  
  /**
   * @OneToMany(targetEntity="RoleResource", mappedBy="resource")
   * @var ArrayCollection
   */
  private $roleResources;

  public function __construct() {
    $this->roleResources = new ArrayCollection();
    $this->children      = new ArrayCollection();
  }

  /**
   * @return Resource
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * @param Resource $parent
   * @return Resource
   */
  public function setParent(Resource $parent) {
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
    return $this->getResourceId();
  }

}