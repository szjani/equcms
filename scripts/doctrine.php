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

// Console
$cli = new \Symfony\Component\Console\Application(
    'Doctrine Command Line Interface',
    \Doctrine\Common\Version::VERSION
);

$helperSet = array();
try {
    // Bootstrapping Console HelperSet
    $helperSet['db']     = new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection());
    $helperSet['em']     = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em);
    $helperSet['dialog'] = new \Symfony\Component\Console\Helper\DialogHelper();
} catch (\Exception $e) {
    $cli->renderException($e, new \Symfony\Component\Console\Output\ConsoleOutput());
}

$cli->setCatchExceptions(true);
$cli->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet($helperSet));

$cli->addCommands(array(
  // DBAL Commands
  new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
  new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

  // ORM Commands
  new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
  new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
  new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
  new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
  new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
  new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
  new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
  new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
  new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
  new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),

  // Migrations Commands
  new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
  new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
  new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
  new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
  new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
  new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
));

$cli->run();