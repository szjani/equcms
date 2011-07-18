<?php
namespace configs\fixtures;

use
  Doctrine\ORM\EntityManager,
  Doctrine\Common\DataFixtures\FixtureInterface,
  Doctrine\Common\DataFixtures\OrderedFixtureInterface,
  Doctrine\Common\DataFixtures\AbstractFixture;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface {

  public function load($manager) {
    $szjani = new \entities\User('szjani@szjani.hu', 'szjani');
    $szjani->setParent($this->getReference('usergroup-administrators'));
    $manager->persist($szjani);
    
    $manager->flush();
  }
  
  public function getOrder() {
    return 25;
  }

}