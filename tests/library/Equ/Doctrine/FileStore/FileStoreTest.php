<?php

namespace Equ\Doctrine\FileStore;
use FileStore\Fixture\Asset;

class FileStoreTest extends \PHPUnit_Framework_TestCase {

  public $backupGlobals = false;

  const TEST_ENTITY_CLASS = "FileStore\Fixture\Asset";
  private $em;

  public function setUp() {
    $classLoader = new \Doctrine\Common\ClassLoader('FileStore\Fixture', __DIR__ . '/../');
    $classLoader->register();

    $config = new \Doctrine\ORM\Configuration();
    $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
    $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
    $config->setProxyDir(__DIR__ . '/Proxy');
    $config->setProxyNamespace('Equ\Doctrine\FileStore\Proxy');
    $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());

    $conn = array(
      'driver' => 'pdo_sqlite',
      'memory' => true,
    );

    $evm = new \Doctrine\Common\EventManager();
    $treeListener = new FileStoreListener();
    $evm->addEventSubscriber($treeListener);
    $this->em = \Doctrine\ORM\EntityManager::create($conn, $config, $evm);

    $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
    $schemaTool->dropSchema(array());
    $schemaTool->createSchema(array(
      $this->em->getClassMetadata(self::TEST_ENTITY_CLASS)
    ));
  }

  public function testStoreNewFile() {
    $asset = new Asset();
    $filename = __DIR__ . '/Fixture/testfile.ext';
    file_put_contents($filename, 'test');
    $asset->setFile($filename);
    $this->em->persist($asset);

    self::assertEquals(1, preg_match('/[0-9a-z]{32}\.ext/', $asset->getFile()));
    self::assertEquals('testfile.ext', $asset->getOriginalFilename());
    self::assertTrue(file_exists($asset->getFile()));
    self::assertEquals(4, $asset->getFileSize());
    self::assertEquals(md5_file($filename), $asset->getFileHash());
    self::assertEquals('text/plain', $asset->getMimeType());
    unlink($filename);
    unlink($asset->getFile());
  }

  public function testRemoveEntity() {
    $asset = new Asset();
    $filename = __DIR__ . '/Fixture/testfile.ext';
    file_put_contents($filename, 'test');
    $asset->setFile($filename);
    $this->em->persist($asset);

    $storedFilename = $asset->getFile();
    self::assertTrue(file_exists($storedFilename));
    $this->em->remove($asset);
    self::assertFalse(file_exists($storedFilename));
  }

  public function testUpdateEntity() {
    $asset = new Asset();
    $filename = __DIR__ . '/Fixture/testfile.ext';
    file_put_contents($filename, 'test');
    $asset->setFile($filename);
    $this->em->persist($asset);
    $this->em->flush();
    $firstFilename = $asset->getFile();

    $filename2 = __DIR__ . '/Fixture/testfile2.ext';
    file_put_contents($filename2, 'test2');
    $asset->setFile($filename2);
    $this->em->persist($asset);
    $this->em->flush();

    self::assertEquals('testfile2.ext', $asset->getOriginalFilename());
    self::assertEquals(5, $asset->getFileSize());
    self::assertTrue(file_exists($asset->getFile()));
    self::assertFalse(file_exists($firstFilename));
    self::assertEquals(md5_file($asset->getFile()), $asset->getFileHash());

    unlink($filename);
    unlink($filename2);
    unlink($asset->getFile());
  }

}