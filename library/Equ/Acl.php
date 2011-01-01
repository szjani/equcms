<?php
namespace Equ;
use Doctrine\ORM\EntityManager;
use entities\Role;
use entities\UserGroup;
use entities\Resource;
use entities\Mvc;
use Equ\Acl\Exception;

class Acl extends \Zend_Acl {

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
   * Build ACL object from database
   */
  public function __construct(EntityManager $em) {
    $this->setEntityManager($em);
    $roles = $em->getRepository('entities\Role')->findAll();
    /* @var $role Role */
    foreach ($roles as $role) {
      $this->addRole($role, $role->getParent());
    }

    $resources = $em->getRepository('entities\Resource')->findAll();
    /* @var $resource Resource */
    foreach ($resources as $resource) {
      $this->add($resource, $resource->getParent());
    }

    $roleResources = $em->getRepository('entities\RoleResource')->findAll();
    /* @var $roleResource RoleResource */
    foreach ($roleResources as $roleResource) {
      $this->allow($roleResource->getRole(), $roleResource->getResource(), $roleResource->getPrivilege());
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
        return parent::isAllowed($role, $resource, $privilege);
      } catch (\Zend_Acl_Exception $e) {
        $parts = \explode(Mvc::SEPARATOR, $resource);
        array_pop($parts);
        $resource = empty($parts) ? null : \implode(Mvc::SEPARATOR, $parts);
      }
    }
  }

}