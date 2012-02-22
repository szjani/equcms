<?php
define('APPLICATION_ENV', 'development');

require_once '../defines.php';
require_once APPLICATION_PATH . '/Bootstrap.php';

$bootstrap = new Bootstrap(APPLICATION_ENV);
$bootstrap->init();

/* @var $em Doctrine\ORM\EntityManager */
$em = $bootstrap->getContainer()->get('doctrine.entitymanager');

use
  Doctrine\Common\DataFixtures\Loader,
  Doctrine\Common\DataFixtures\Executor\ORMExecutor,
  Doctrine\Common\DataFixtures\Purger\ORMPurger;

$loader = new Loader();
$loader->loadFromDirectory(APPLICATION_PATH . '/configs/fixtures');
$purger   = new ORMPurger();
$executor = new ORMExecutor($em, $purger);
$executor->execute($loader->getFixtures());