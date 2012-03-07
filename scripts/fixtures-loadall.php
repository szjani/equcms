<?php
define('APPLICATION_ENV', 'development');

require_once '../defines.php';

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap('gedmo');

/* @var $em Doctrine\ORM\EntityManager */
$em = $application->getBootstrap()->getContainer()->get('doctrine.entitymanager');

use
  Doctrine\Common\DataFixtures\Loader,
  Doctrine\Common\DataFixtures\Executor\ORMExecutor,
  Doctrine\Common\DataFixtures\Purger\ORMPurger;

$loader = new Loader();
$loader->loadFromDirectory(APPLICATION_PATH . '/configs/fixtures');
$purger   = new ORMPurger();
$executor = new ORMExecutor($em, $purger);
$executor->execute($loader->getFixtures());