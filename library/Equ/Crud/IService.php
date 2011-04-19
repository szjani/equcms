<?php
namespace Equ\Crud;
use
  Equ\DTO,
  Doctrine\ORM\Query;

/**
 * Interface for CRUD service.
 * CRUD controller use an implementation of this interface.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
interface IService {

  /**
   * Retrieves the entity identified by $id, or a new one.
   * If $id is incorrect, throws an exception.
   *
   * @throws Exception
   * @param int $id
   * @return object
   */
  public function getEntity($id = null);

  /**
   * Create a record
   *
   * @param DTO $dto
   * @return object
   */
  public function create(DTO $dto);

  /**
   * Update a record
   *
   * @param int $id
   * @param DTO $dto
   * @return object
   */
  public function update($id, DTO $dto);

  /**
   * Delete a record
   *
   * @param int $id
   */
  public function delete($id);

  /**
   * @return array of column names
   */
  public function getTableFieldNames();

  /**
   * @param int $page
   * @param int $itemPerPage
   * @param string $sort database field name
   * @param string $order order direction (ASC/DESC)
   * @param boolean $showDeleted
   * @param DTO $filters
   * @param Query $query
   * @return \Zend_Paginator
   */
  public function getPagePaginator($page = 1, $itemPerPage = 10, $sort = null, $order = 'ASC', DTO $filters = null, $query = null);
}