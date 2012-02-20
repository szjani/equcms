<?php
namespace entities;
use
  Gedmo\Tree\Entity\Repository\NestedTreeRepository,
  Equ\Crud\LookUpable;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class RoleRepository extends NestedTreeRepository implements LookUpable {
  
  public function findForLookUp($search, $key, $value) {
    return $this->createQueryBuilder('r')
      ->select("r.id as $key, r.role as $value")
      ->where('r.role LIKE :search')
      ->orderBy('r.role')
      ->setMaxResults(3)
      ->setParameter('search', '%' . $search . '%')
      ->getQuery()
      ->getArrayResult();
  }
  
  public function findOneForLookUp($id, $key, $value) {
    $obj = $this->find($id);
    return array(
      $key => $obj->getId(),
      $value => $obj->getRoleId()
    );
  }
  
}