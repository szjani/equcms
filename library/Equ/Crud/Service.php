<?php
namespace Equ\Crud;
use
  Doctrine\ORM\EntityManager,
  Equ\DTO\EntityBuilder,
  Doctrine\ORM\Query,
  Doctrine\ORM\QueryBuilder,
  Equ\DTO,
  Equ\AbstractService,
  Equ\Crud\IService as ICrudService;

/**
 * Service class to CRUD methods
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       2.0
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Service extends AbstractService implements ICrudService {

  /**
   * @var \Equ\Entity\Visitable
   */
  private $entity = null;

  /**
   * @var EntityBuilder
   */
  private $entityBuilder = null;

  private $entityClass = null;

  /**
   * Retrieves the type of the handled entity
   *
   * @return string
   */
  public function getEntityClass() {
    return $this->entityClass;
  }

  /**
   * @param string $entityClass
   * @return Service
   */
  public function setEntityClass($entityClass) {
    $this->entityClass = (string)$entityClass;
    return $this;
  }

  /**
   * @param string $entityClass
   */
  public function __construct($entityClass) {
    $this->setEntityClass($entityClass);
  }

  public function preCreate(DTO $values) {}
  public function postCreate() {}
  public function preUpdate($id, DTO $values) {}
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
   * @return \Equ\Entity\Visitable
   */
  public function getEntity($id = null) {
    if ($this->entity === null || $id != $this->getFieldValue($this->entity, $this->getIdentifierFieldName())) {
      if ($id === null) {
        $entity = $this->getEntityManager()->getClassMetadata($this->getEntityClass())->newInstance();
        if ($entity instanceof \Equ\Entity\IFormBase) {
          $entity->init();
        }
        $this->entity = $entity;
      } else {
        $entity = $this->getEntityManager()->getRepository($this->getEntityClass())->find($id);
        if (!$entity) {
          throw new Exception("Invalid id '$id'");
        }
        if ($entity instanceof \Equ\Entity\IFormBase) {
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
   * @param DTO $dto
   * @return object
   */
  public function create(DTO $dto) {
    $em = $this->getEntityManager();
    $em->beginTransaction();
    try {
      $this->preCreate($dto);
      $entityBuilder = $this->getEntityBuilder();
      $dto->accept($entityBuilder);
      $entity = $entityBuilder->getEntity();
      $em->persist($entity);
      $this->postCreate();
      $em->flush();
      $em->commit();
      return $entity;
    } catch (\Exception $e) {
      $em->rollback();
      $em->close();
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
    $em = $this->getEntityManager();
    $em->beginTransaction();
    try {
      $this->preUpdate($id, $dto);
      if ($id === null) {
        throw new Exception("Invalid id '$id'");
      }
      $entity = $this->getEntity($id);
      $entityBuilder = $this->getEntityBuilder();
      $entityBuilder->setEntity($entity);
      $dto->accept($entityBuilder);
      $em->persist($entity);
      $this->postUpdate($id);
      $em->flush();
      $em->commit();
      return $entity;
    } catch (\Exception $e) {
      $em->rollback();
      $em->close();
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
    $em = $this->getEntityManager();
    $em->beginTransaction();
    try {
      $this->preDelete($id);
      if ($id === null) {
        throw new Exception("Invalid id '$id'");
      }
      $entity = $this->getEntity($id);
      $em->remove($entity);
      $this->postDelete($id);
      $em->flush();
      $em->commit();
    } catch (\Exception $e) {
      $em->rollback();
      $em->close();
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
   * @param DTO $filters
   * @param Query $query
   * @return \Zend_Paginator
   */
  public function getPagePaginator($page = 1, $itemPerPage = 10, $sort = null, $order = 'ASC', DTO $filters = null, $query = null) {
    try {
      if ($query === null) {
        $query = $this->getListQuery($filters, $sort, $order);
      }
//      $query->setHydrationMode(Query::HYDRATE_ARRAY);
      $adapter   = new \Equ\Paginator\Adapter\Doctrine($query);
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
   *
   * @param DTO $filters
   * @param string $sort
   * @param string $order
   * @return Query
   */
  public function getListQuery(DTO $filters = null, $sort = null, $order = 'ASC') {
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

      if ($filters !== null) {
        foreach ($filters as $name => $value) {
          if ($metadata->hasField($name) && $value !== null && $value !== '') {
            $columnDefinition = $metadata->getFieldMapping($name);
            switch ($columnDefinition['type']) {
              case 'integer':
                $queryBuilder
                  ->andWhere("m." . $name . " = :filter")
                  ->setParameter('filter', (int)$value);
                break;
              case 'boolean':
                $queryBuilder
                  ->andWhere("m." . $name . " = :filter")
                  ->setParameter('filter', (boolean)$value);
                break;
              default:
                $queryBuilder
                  ->andWhere("m." . $name . " LIKE :filter")
                  ->setParameter('filter', '%'.$value.'%');
                break;
            }
          }
        }
      }
      return $queryBuilder->getQuery();
    } catch (\Exception $e) {
      $this->getLog()->err($e);
      throw $e;
    }
  }
}