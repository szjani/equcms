<?php
namespace configs\fixtures;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadNavigation extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 20;
    }

    public function load(ObjectManager $manager)
    {
        $root = new \entities\Resource('root');
        $manager->persist($root);
        $this->addReference('resource-root', $root);

        $mvcRoot = new \entities\Mvc();
        $mvcRoot->setParent($root);
        $manager->persist($mvcRoot);

        // level 1
        $mainRoot = new \entities\Mvc();
        $mainRoot
            ->setModule('index')
            ->setController('index')
            ->setParent($mvcRoot);
        $manager->persist($mainRoot);

        $adminRoot = new \entities\Mvc();
        $adminRoot
            ->setModule('index')
            ->setController('admin')
            ->setParent($mvcRoot);
        $manager->persist($adminRoot);

        // level 2
        $mvc = new \entities\Mvc();
        $mvc
            ->setModule('mvc')
            ->setController('admin')
            ->setParent($adminRoot);
        $manager->persist($mvc);

        $user = new \entities\Mvc();
        $user
            ->setModule('user')
            ->setController('admin')
            ->setParent($adminRoot);
        $manager->persist($user);

        $group = new \entities\Mvc();
        $group
            ->setModule('group')
            ->setController('admin')
            ->setParent($adminRoot);
        $manager->persist($group);

        $permission = new \entities\Mvc();
        $permission
            ->setModule('permission')
            ->setController('admin')
            ->setParent($adminRoot);
        $manager->persist($permission);

        $cache = new \entities\Mvc();
        $cache
            ->setModule('cache')
            ->setController('admin')
            ->setParent($adminRoot);
        $manager->persist($cache);

        $this->addReference('mvc-admin-mvc', $mvc);
        $this->addReference('mvc-admin-user', $user);
        $this->addReference('mvc-admin-group', $group);
        $this->addReference('mvc-admin-permission', $permission);

        // level 3
        $rec = new \entities\Mvc();
        $rec
            ->setModule('mvc')
            ->setController('admin')
            ->setAction('list')
            ->setParent($mvc);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('mvc')
            ->setController('admin')
            ->setAction('create')
            ->setParent($mvc);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('mvc')
            ->setController('admin')
            ->setAction('update')
            ->setParent($mvc);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('user')
            ->setController('admin')
            ->setAction('list')
            ->setParent($user);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('user')
            ->setController('admin')
            ->setAction('create')
            ->setParent($user);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('user')
            ->setController('admin')
            ->setAction('update')
            ->setParent($user);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('group')
            ->setController('admin')
            ->setAction('list')
            ->setParent($group);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('group')
            ->setController('admin')
            ->setAction('create')
            ->setParent($group);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('group')
            ->setController('admin')
            ->setAction('update')
            ->setParent($group);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('permission')
            ->setController('admin')
            ->setAction('list')
            ->setParent($permission);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('permission')
            ->setController('admin')
            ->setAction('create')
            ->setParent($permission);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('permission')
            ->setController('admin')
            ->setAction('update')
            ->setParent($permission);
        $manager->persist($rec);

        $rec = new \entities\Mvc();
        $rec
            ->setModule('cache')
            ->setController('admin')
            ->setAction('list')
            ->setParent($cache);
        $manager->persist($rec);

        $manager->flush();

        $this->addReference('mvc-root', $mvcRoot);
        $this->addReference('mvc-admin', $adminRoot);
    }

}