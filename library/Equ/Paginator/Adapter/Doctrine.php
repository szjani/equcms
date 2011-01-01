<?php
namespace Equ\Paginator\Adapter;

use DoctrineExtensions\Paginate\PaginationAdapter;
use Doctrine\ORM\Query;

class Doctrine extends PaginationAdapter {

  /**
   * Gets the current page of items
   *
   * @param string $offset
   * @param string $itemCountPerPage
   * @return void
   * @author David Abdemoulaie
   */
  public function getItems($offset, $itemCountPerPage) {
    $ids = $this->createLimitSubquery($offset, $itemCountPerPage)->getScalarResult();
    $ids = array_map(
      function ($e) {
        return current($e);
      },
      $ids
    );
//      var_dump($this->query->getDQL());
      $res = $this->createWhereInQuery($ids)->execute();
      foreach ($res as $obj) {
//        var_dump(get_class($obj));
//        \Doctrine\Common\Util\Debug::dump($obj);
      }
//      exit;
    return $this->createWhereInQuery($ids)->execute();
  }

}