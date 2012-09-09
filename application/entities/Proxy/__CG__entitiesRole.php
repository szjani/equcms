<?php

namespace entities\Proxy\__CG__\entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Role extends \entities\Role implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getParent()
    {
        $this->__load();
        return parent::getParent();
    }

    public function setParent(\entities\Role $parent = NULL)
    {
        $this->__load();
        return parent::setParent($parent);
    }

    public function getRoleResources()
    {
        $this->__load();
        return parent::getRoleResources();
    }

    public function getRoleId()
    {
        $this->__load();
        return parent::getRoleId();
    }

    public function setRoleId($roleId)
    {
        $this->__load();
        return parent::setRoleId($roleId);
    }

    public function __toString()
    {
        $this->__load();
        return parent::__toString();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setValidator(\Equ\Object\Validator $validator)
    {
        $this->__load();
        return parent::setValidator($validator);
    }

    public function getValidator()
    {
        $this->__load();
        return parent::getValidator();
    }

    public function validate()
    {
        $this->__load();
        return parent::validate();
    }

    public function offsetExists($offset)
    {
        $this->__load();
        return parent::offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        $this->__load();
        return parent::offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__load();
        return parent::offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->__load();
        return parent::offsetUnset($offset);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'lft', 'rgt', 'lvl', 'role', 'parent', 'children', 'roleResources');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}