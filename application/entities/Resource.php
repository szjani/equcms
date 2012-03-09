<?php
namespace entities;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Resource entity
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="`resource`", indexes={
 *   @ORM\Index(name="resource_lft_idx", columns={"lft"}),
 *   @ORM\Index(name="resource_rgt_idx", columns={"rgt"}),
 *   @ORM\Index(name="resource_lvl_idx", columns={"lvl"}),
 *   @ORM\Index(name="resource_discr_idx", columns={"discr"})
 * })
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"mvc" = "Mvc"})
 */
abstract class Resource extends \Equ\Entity implements \Zend_Acl_Resource_Interface, \Serializable {

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue
   * @var int
   */
  protected $id;

  /**
   * @Gedmo\TreeParent
   * @ORM\ManyToOne(targetEntity="Resource", inversedBy="children")
   * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="cascade")
   * @var Resource
   */
  protected $parent;

  /**
   * @ORM\OneToMany(targetEntity="Resource", mappedBy="parent")
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
   * @Gedmo\TreeLevel
   * @ORM\Column(name="lvl", type="integer")
   */
  protected $lvl;

  /**
   * @ORM\Column(name="resource", type="string", length=255, nullable=false, unique=true)
   * @var string
   */
  protected $resource;
  
  /**
   * @ORM\OneToMany(targetEntity="RoleResource", mappedBy="resource")
   * @var ArrayCollection
   */
  protected $roleResources;

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
  public function setParent(Resource $parent = null) {
    $this->parent = $parent;
    return $this;
  }

  /**
   * @return ArrayCollection
   */
  public function getRoleResources() {
    return $this->roleResources;
  }

  public function getResourceId() {
    return $this->resource;
  }

  protected function setResourceId($resource) {
    $this->resource = (string)$resource;
    return $this;
  }

  public function __toString() {
    return $this->getResourceId();
  }

  public function getId() {
    return $this->id;
  }

  public function serialize() {
    return \serialize(array(
      'id' => $this->getId(),
      'lft' => $this->lft,
      'rgt' => $this->rgt,
      'lvl' => $this->lvl,
      'resource' => $this->resource
    ));
  }

  public function unserialize($serialized) {
    $serialized = \unserialize($serialized);
    $this->id = $serialized['id'];
    $this->lft = $serialized['lft'];
    $this->rgt = $serialized['rgt'];
    $this->lvl = $serialized['lvl'];
    $this->resource = $serialized['resource'];
    $this->roleResources = new ArrayCollection();
    $this->children      = new ArrayCollection();
  }

  public static function getDisplayField() {
    return 'resource';
  }

}