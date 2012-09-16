<?php
namespace library;

use Doctrine\ORM\EntityManager;
use library\LazyAcl\RoleRegistry;
use entities\Role;
use entities\UserGroup;
use entities\Resource;
use entities\Mvc;
use Zend_Cache_Core;

class LazyAcl extends \Zend_Acl
{
    const KEY = 'acl';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $em
     * @return Acl
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $em
     * @param Zend_Cache_Core $cache 
     */
    public function __construct(EntityManager $em, Zend_Cache_Core $cache)
    {
        $this->setEntityManager($em);
        $this->_roleRegistry = new RoleRegistry($this);

        $resources = $cache->load(self::KEY);
        if ($resources !== false) {
            $this->_resources = $resources;
        } else {
            $query = $em->createQuery('SELECT r, p FROM entities\Resource r LEFT JOIN r.parent p ORDER BY r.lvl');
            $resources = $query->getResult();
            /* @var $resource Resource */
            foreach ($resources as $resource) {
                $this->addResource($resource->getResourceId(), $resource->getParent());
            }
            $cache->save($this->_resources, self::KEY);
        }
    }

    /**
     * @param string $role
     * @param string $resource
     * @param string $privilege
     * @return boolean
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        while ($resource !== null) {
            try {
                return parent::isAllowed($role, $resource, $privilege);
            } catch (\Zend_Acl_Exception $e) {
                if ($resource instanceof \Zend_Acl_Resource_Interface) {
                    $resource = $resource->getResourceId();
                }
                if (\substr($resource, 0, 4) !== 'mvc:' || $resource == 'mvc:') {
                    throw $e;
                } else {
                    $parts = \explode('.', \substr($resource, 4));
                    array_pop($parts);
                    $resource = 'mvc:' . (empty($parts) ? '' : \implode('.', $parts));
                }
            }
        }
    }

}