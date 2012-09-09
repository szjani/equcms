<?php
namespace entities;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Equ\Navigation\ItemRepository;
use Doctrine\ORM\Query\Expr;

class MvcRepository extends NestedTreeRepository implements ItemRepository
{

    /**
     * @return array of \Equ\Navigation\Item
     */
    public function getNavigationItems()
    {
        return $this->createQueryBuilder('m')
            ->select('m, p')
            ->leftJoin('m.parent', 'p', Expr\Join::WITH, 'p INSTANCE OF ' . Mvc::className())
            ->orderBy('m.lvl')
            ->addOrderBy('m.lft')
            ->getQuery()
            ->getResult();
    }

}