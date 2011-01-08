<?php
namespace Equ\Crud;
use Doctrine\ORM\EntityManager;
use Equ\DTO\EntityBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use DTO;

/**
 * Abstract service class to CRUD methods
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
abstract class Service extends \Equ\AbstractService {

  /**
   * @var \Equ\Entity\Visitable
   */
  private $entity = null;

  /**
   * @var EntityBuilder
   */
  private $entityBuilder = null;

  /**
   * Retrieves the type of the handled entity
   *
   * @return string
   */
  public abstract function getEntityClass();

  public function preCreate(array $values) {}
  public function postCreate() {}
  public function preUpdate($id, array $values) {}
  public function postUpdate($id) {}
  public function preDelete($id) {}
  public function postDelete($id) {}

  /**
   * @param EntityBuilder $entityBuilder
   * @return Service
   */
  public final function setEntityBuilder(EntityBuilder $entityBuilder) {
    $this->entityBuilder = $entityBuilder;
    return $this;
  }

  /**
   * @return EntityBuilder
   */
  public final function getEntityBuilder() {
    if ($this->entityBuilder === null) {
      $this->entityBuilder = new EntityBuilder($this->getEntityManager(), $this->getEntityClass());
    }
    return $this->entityBuilder;
  }

  /**
   * @return string
   */
  public final function getModuleName() {
    $arr = explode('_', get_class($this));
    return array_shift($arr);
  }

  /**
   * @return array of column names
   */
  public function getTableFieldNames() {
    $metadata = $this->getEntityManager()->getClassMetadata($this->getEntityClass());
//    $fields = array_diff($metadata->fieldNames, $metadata->identifier);
    $fields = $metadata->fieldNames;
    foreach ($metadata->associationMappings as $fieldName => $def) {
      if ($def['isOwningSide']) {
        $fields[] = $fieldName;
      }
    }
//    $fields = array_merge($fields, array_keys($metadata->associationMappings));
//    $discColumn = isset($metadata->discriminatorColumn['name']) ? array($metadata->discriminatorColumn['name']) : array();
    return $fields;
  }

  protected function getIdentifierFieldName() {
    return $this->getEntityManager()->getClassMetadata($this->getEntityClass())->getSingleIdentifierFieldName();
  }

  protected function getFieldValue($entity, $field) {
    return $this->getEntityManager()->getClassMetadata(\get_class($entity))->getFieldValue($entity, $field);
  }

  /**
   * Retrieves the entity identified by $id, or a new one.
   * If $id is incorrect, throws an exception.
   *
   * @throws Exception
   * @param int $id
   * @return object
   */
  public function getEntity($id = null) {
    if ($this->entity === null || $id != $this->getFieldValue($this->entity, $this->getIdentifierFieldName())) {
      if ($id === null) {
        $entity = $this->getEntityManager()->getClassMetadata($this->getEntityClass())->newInstance();
        if ($entity instanceof \Equ\Entity\FormBase) {
          $entity->init();
        }
        $this->entity = $entity;
      } else {
        $entity = $this->getEntityManager()->getRepository($this->getEntityClass())->find($id);
        if (!$entity) {
          throw new Exception("Invalid id '$id'");
        }
        if ($entity instanceof \Equ\Entity\FormBase) {
          $entity->init();
        }
        $this->entity = $entity;
      }
    }
    return $this->entity;
  }

  /**
   * Create a record
   *
   * @param array $$dto
   * @return object
   */
  public function create(DTO $dto) {
    $this->preCreate($dto);
    try {
      $entityBuilder = $this->getEntityBuilder();
      $dto->accept($entityBuilder);
      $entity = $entityBuilder->getEntity();
      $this->getEntityManager()->persist($entity);
      $this->getEntityManager()->flush();
      $this->postCreate();
      return $entity;
    } catch (\Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }

  /**
   * Update a record
   *
   * @param int $id
   * @param DTO $dto
   * @return object
   */
  public function update($id, DTO $dto) {
    $this->preUpdate($id, $dto);
    try {
      if ($id === null) {
        throw new Exception("Invalid id '$id'");
      }
      $entity = $this->getEntity($id);
      $entityBuilder = $this->getEntityBuilder();
      $entityBuilder->setEntity($entity);
      $dto->accept($entityBuilder);
      $this->getEntityManager()->persist($entity);
      $this->getEntityManager()->flush();
      $this->postUpdate($id);
      return $entity;
    } catch (\Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }

  /**
   * Delete a record
   *
   * @param int $id
   */
  public function delete($id) {
    $this->preDelete($id);
    try {
      if ($id === null) {
        throw new Exception("Invalid id '$id'");
      }
      $entity = $this->getEntity($id);
      $this->getEntityManager()->remove($entity);
      $this->getEntityManager()->flush();
      $this->postDelete($id);
    } catch (\Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }

  /**
   * @param int $page
   * @param int $itemPerPage
   * @param string $sort database field name
   * @param string $order order direction (ASC/DESC)
   * @param boolean $showDeleted
   * @param array $filters
   * @param Query|null $query
   */
  public function getPagePaginator($page = 1, $itemPerPage = 10, $sort = null, $order = 'ASC', array $filters = array(), $query = null) {
    try {
      if ($query === null) {
        $query = $this->getListQuery($filters, $sort, $order);
      }
//      $query->setHydrationMode(Query::HYDRATE_ARRAY);
      $adapter = new \Equ\Paginator\Adapter\Doctrine($query);
      $paginator = new \Zend_Paginator($adapter);
      $paginator
        ->setCurrentPageNumber($page)
        ->setItemCountPerPage($itemPerPage);
      return $paginator;
    } catch (\Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }

  /**
   * @param int $page
   * @param int $itemPerPage
   * @return Query
   */
  public function getListQuery(array $filters = array(), $sort = null, $order = 'ASC') {
    try {
      $metadata = $this->getEntityManager()->getClassMetadata($this->getEntityClass());

      $queryBuilder = new QueryBuilder($this->getEntityManager());
      $queryBuilder
        ->select('m')
        ->from($this->getEntityClass(), 'm');

      foreach (array_keys($metadata->associationMappings) as $relatedName) {
        $queryBuilder->leftJoin("m.$relatedName", $relatedName);
        $queryBuilder->addSelect($relatedName);
      }

      if ($sort !== null) {
        $queryBuilder->orderBy('m.' . $sort, $order == 'ASC' ? 'ASC' : 'DESC');
      }

//      if ($form = $this->getFilterForm()) {
//        if (!empty($filters) && $form->isValid($filters)) {
//          foreach ($form->getElements() as $element) {
//            if ($metadata->hasField($element->getName())) {
//              $columnDefinition = $metadata->getFieldMapping($element->getName());
//              $value = $element->getValue();
//              if ($value !== null && $value !== '') {
//                switch ($columnDefinition['type']) {
//                  case 'integer':
//                    $queryBuilder
//                      ->andWhere("m." . $element->getName() . " = :filter")
//                      ->setParameter('filter', (int)$value);
//                    break;
//                  case 'boolean':
//                    $queryBuilder
//                      ->andWhere("m." . $element->getName() . " = :filter")
//                      ->setParameter('filter', (boolean)$value);
//                    break;
//                  default:
//                    $queryBuilder
//                      ->andWhere("m." . $element->getName() . " LIKE :filter")
//                      ->setParameter('filter', '%'.$value.'%');
//                    break;
//                }
//              }
//            }
//          }
//        }
//      }
      return $queryBuilder->getQuery();
    } catch (\Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }
}