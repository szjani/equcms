<?php

namespace entities\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class entitiesUserGroupProxy extends \entities\UserGroup implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function getRoleId()
    {
        $this->_load();
        return parent::getRoleId();
    }

    public function getName()
    {
        $this->_load();
        return parent::getName();
    }

    public function setName($name)
    {
        $this->_load();
        return parent::setName($name);
    }

    public function serialize()
    {
        $this->_load();
        return parent::serialize();
    }

    public function unserialize($serialized)
    {
        $this->_load();
        return parent::unserialize($serialized);
    }

    public function getParent()
    {
        $this->_load();
        return parent::getParent();
    }

    public function setParent(\entities\UserGroup $parent = NULL)
    {
        $this->_load();
        return parent::setParent($parent);
    }

    public function getRoleResources()
    {
        $this->_load();
        return parent::getRoleResources();
    }

    public function __toString()
    {
        $this->_load();
        return parent::__toString();
    }

    public function getId()
    {
        $this->_load();
        return parent::getId();
    }

    public function init()
    {
        $this->_load();
        return parent::init();
    }

    public function accept(\Equ\EntityVisitor $visitor)
    {
        $this->_load();
        return parent::accept($visitor);
    }

    public function addFieldValidator($fieldName, \Zend_Validate_Abstract $validator)
    {
        $this->_load();
        return parent::addFieldValidator($fieldName, $validator);
    }

    public function clearFieldValidators($fieldName)
    {
        $this->_load();
        return parent::clearFieldValidators($fieldName);
    }

    public function getFieldValidators($fieldName)
    {
        $this->_load();
        return parent::getFieldValidators($fieldName);
    }

    public function validate()
    {
        $this->_load();
        return parent::validate();
    }

    public function offsetExists($offset)
    {
        $this->_load();
        return parent::offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        $this->_load();
        return parent::offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->_load();
        return parent::offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->_load();
        return parent::offsetUnset($offset);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'lft', 'rgt', 'lvl', 'role', 'name', 'parent', 'children', 'roleResources');
    }
}