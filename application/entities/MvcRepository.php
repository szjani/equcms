<?php
namespace entities;
use
  Gedmo\Tree\Entity\Repository\NestedTreeRepository,
  Equ\Navigation\ItemRepository;

class MvcRepository extends NestedTreeRepository implements ItemRepository {
  
  /**
   * @return array of \Equ\Navigation\Item
   */
  public function getNavigationItems() {
    return $this->createQueryBuilder('m')
      ->select('m, p')
      ->leftJoin('m.parent', 'p')
      ->orderBy('m.lvl')
      ->addOrderBy('m.lft')
      ->getQuery()
      ->getResult();
  }
  
}