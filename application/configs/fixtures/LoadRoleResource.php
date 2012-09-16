<?php
namespace configs\fixtures;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRoleResource extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $rec = new \entities\RoleResource();
        $rec->setRole($this->getReference('usergroup-everybody'));
        $rec->setResource($this->getReference('mvc-root'));
        $rec->setAllowed();
        $manager->persist($rec);

        $rec = new \entities\RoleResource();
        $rec->setRole($this->getReference('usergroup-administrators'));
        $rec->setResource($this->getReference('mvc-admin'));
        $rec->setAllowed();
        $manager->persist($rec);

        $rec = new \entities\RoleResource();
        $rec->setRole($this->getReference('usergroup-everybody'));
        $rec->setResource($this->getReference('mvc-admin'));
        $rec->setAllowed(false);
        $manager->persist($rec);

        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }

}