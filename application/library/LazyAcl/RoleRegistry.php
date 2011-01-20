<?php
namespace library\LazyAcl;
use library\LazyAcl;
use Doctrine\ORM\EntityManager;
use entities\Role;
use library\LazyAcl\Exception;

class RoleRegistry extends \Zend_Acl_Role_Registry {

  /**
   * @var LazyAcl
   */
  private $acl;

  /**
   * @var string
   */
  private $activeRole = null;

  /**
   * @param LazyAcl $acl
   */
  public function __construct(LazyAcl $acl) {
    $this->acl = $acl;
  }

  /**
   * @return EntityManager
   */
  protected function getEntityManager() {
    return $this->acl->getEntityManager();
  }

  protected function storePermissionsByDb($role) {
    /* @var $roleResource \entities\RoleResource */
    foreach ($role->getRoleResources() as $roleResource) {
      if ($roleResource->isAllowed()) {
        $this->acl->allow($roleResource->getRole(), $roleResource->getResource(), $roleResource->getPrivilege());
      } else {
        $this->acl->deny($roleResource->getRole(), $roleResource->getResource(), $roleResource->getPrivilege());
      }
    }
  }

  protected function storeRoleByDb($role) {
    $repo = $this->getEntityManager()->getRepository('entities\Role');
    if (\is_string($role)) {
      $role = $repo->findOneBy(array('role' => (string)$role));
    }
    if (!($role instanceof Role)) {
      throw new Exception("Invalid role '{(string)$role}'");
    }
    /* @var $role entities\Role */
    $nodes = $repo->getPath($role);

    /* @var $node entities\Role */
    foreach ($nodes as $node) {
      $this->activeRole = (string)$node;
      $parent = $node->getParent();
      $this->add($node, ($parent === null) ? null : $parent);
      $this->storePermissionsByDb($node);
    }
    return true;
  }

  public function has($role) {
    $exists = parent::has($role);
    if ($exists) {
      return true;
    }
    if ($this->activeRole === (string)$role) {
      return false;
    }
    return $this->storeRoleByDb($role);
  }

}