<?php

namespace configs\fixtures;

use Doctrine\ORM\EntityManager,
    Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;

class LoadUserGroup extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $root = new \entities\Role('root');
        $manager->persist($root);

        $everybody = new \entities\UserGroup('Everybody');
        $everybody->setParent($root);
        $manager->persist($everybody);

        $admin = new \entities\UserGroup('Administrators');
        $admin->setParent($everybody);
        $manager->persist($admin);

        $manager->flush();

        $this->addReference('usergroup-everybody', $everybody);
        $this->addReference('usergroup-administrators', $admin);
    }

    public function getOrder()
    {
        return 10;
    }

}