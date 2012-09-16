<?php
namespace configs\fixtures;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $szjani = new \entities\User('szjani@szjani.hu', 'szjani');
        $szjani->setUserGroup($this->getReference('usergroup-administrators'));
        $manager->persist($szjani);

        $manager->flush();
    }

    public function getOrder()
    {
        return 25;
    }

}