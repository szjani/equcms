<?php
namespace Equ;
use Doctrine\ORM\EntityManager;
use Equ\LazyAcl\RoleRegistry;
use entities\Role;
use entities\UserGroup;
use entities\Resource;
use entities\Mvc;

class LazyAcl extends \Zend_Acl implements \Serializable {

  /**
   * @var EntityManager
   */
  private $entityManager;

  /**
   * @param EntityManager $em
   * @return Acl
   */
  public function setEntityManager(EntityManager $em) {
    $this->entityManager = $em;
    return $this;
  }

  /**
   * @return EntityManager
   */
  public function getEntityManager() {
    return $this->entityManager;
  }

  /**
   * @param EntityManager $em
   */
  public function __construct(EntityManager $em) {
    $this->setEntityManager($em);
    $this->_roleRegistry = new RoleRegistry($this);
    $resources = $em->getRepository('entities\Resource')->findAll();
    /* @var $resource Resource */
    foreach ($resources as $resource) {
      $this->addResource($resource->getResourceId(), $resource->getParent());
    }
  }

  /**
   * @param string $role
   * @param string $resource
   * @param string $privilege
   * @return boolean
   */
  public function isAllowed($role = null, $resource = null, $privilege = null) {
    if (!($resource instanceof Mvc)) {
      return parent::isAllowed($role, $resource, $privilege);
    }
    while ($resource !== null) {
      try {
//        var_dump((string)$resource);
        return parent::isAllowed($role, $resource, $privilege);
      } catch (\Zend_Acl_Exception $e) {
        $resource = $resource->getParent();
//        $parts = \explode(Mvc::SEPARATOR, $resource);
//        array_pop($parts);
//        $resource = empty($parts) ? null : \implode(Mvc::SEPARATOR, $parts);
      }
    }
  }

  public function serialize() {
    return serialize(array(
      '_resources' => $this->_resources,
    ));
  }

  public function unserialize($serialized) {
    $serialized = unserialize($serialized);
    $this->_resources = $serialized['_resources'];
    $this->_roleRegistry = new RoleRegistry($this);
  }


}