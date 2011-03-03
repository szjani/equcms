<?php
namespace library;
use Doctrine\ORM\EntityManager;
use library\LazyAcl\RoleRegistry;
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
    while ($resource !== null) {
      try {
        return parent::isAllowed($role, $resource, $privilege);
      } catch (\Zend_Acl_Exception $e) {
        if ($resource instanceof \Zend_Acl_Resource_Interface) {
          $resource = $resource->getResourceId();
        }
        if (\substr($resource, 0, 4) !== 'mvc:' || $resource == 'mvc:') {
          throw $e;
        } else {
          $parts = \explode('.', \substr($resource, 4));
          array_pop($parts);
          $resource = 'mvc:' . (empty($parts) ? '' : \implode('.', $parts));
        }
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