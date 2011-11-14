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

  protected function storePermissionsByDb(Role $role) {
    /* @var $roleResource \entities\RoleResource */
    foreach ($role->getRoleResources() as $roleResource) {
      if ($roleResource->isAllowed()) {
        $this->acl->allow($role, $roleResource->getResource(), $roleResource->getPrivilege());
      } else {
        $this->acl->deny($role, $roleResource->getResource(), $roleResource->getPrivilege());
      }
    }
  }

  protected function storeRoleByDb($role) {
    $repo = $this->getEntityManager()->getRepository(Role::className());
    /* @var $repo \Gedmo\Tree\Entity\Repository\NestedTreeRepository */
    if (\is_string($role)) {
      $role = $repo->findOneBy(array('role' => (string)$role));
    }
    if (!($role instanceof Role)) {
      return;
    }
    /* @var $role entities\Role */
    $nodes = $repo->getPathQueryBuilder($role)
      ->select('node, rr, res')
      ->leftJoin('node.roleResources', 'rr')
      ->leftJoin('rr.resource', 'res')
      ->getQuery()
      ->getResult();

    /* @var $node entities\Role */
    foreach ($nodes as $node) {
      $this->activeRole = (string)$node;
      if (!$this->has($node)) {
        $parent = $node->getParent();
        $this->add($node, $parent ?: null);
        $this->storePermissionsByDb($node);
      }
    }
  }

  public function has($role) {
    if (!parent::has($role)) {
      if ($this->activeRole === (string)$role) {
        return false;
      }
      $this->storeRoleByDb($role);
    }
    return parent::has($role);
  }

}