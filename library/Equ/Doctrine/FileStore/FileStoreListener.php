<?php

namespace Equ\Doctrine\FileStore;

use
  Doctrine\ORM\Events,
  Doctrine\Common\EventArgs,
  Doctrine\ORM\Event\PreUpdateEventArgs,
  Doctrine\Common\Persistence\ObjectManager,
  Gedmo\Mapping\MappedEventSubscriber,
  Equ\Exception\InvalidArgumentException;

class FileStoreListener extends MappedEventSubscriber {

  /**
   * Specifies the list of events to listen
   *
   * @return array
   */
  public function getSubscribedEvents() {
    return array(
      Events::prePersist,
      Events::preUpdate,
      Events::preRemove,
      Events::loadClassMetadata
    );
  }

  /**
   * @return ObjectManager
   */
  protected function getObjectManager(EventArgs $args) {
    return $args->getEntityManager();
  }

  /**
   * {@inheritdoc}
   */
  protected function getObject(EventArgs $args) {
    return $args->getEntity();
  }

  /**
   * Checks for persisted Nodes
   *
   * @param EventArgs $args
   * @return void
   */
  public function prePersist(EventArgs $args) {
    $om = $this->getObjectManager($args);
    $object = $this->getObject($args);
    $meta = $om->getClassMetadata(get_class($object));

    if ($config = $this->getConfiguration($om, $meta->name)) {
      $this->refreshFile($object, $meta, $config);
    }
  }

  /**
   * @param EventArgs $args
   */
  public function preRemove(EventArgs $args) {
    $om = $this->getObjectManager($args);
    $object = $this->getObject($args);
    $meta = $om->getClassMetadata(get_class($object));

    if ($config = $this->getConfiguration($om, $meta->name)) {
      $filename = $config['path'] . '/' . $meta->getReflectionProperty($config['filename'])->getValue($object);
      if (file_exists($filename)) {
        unlink($filename);
      }
    }
  }

  /**
   * @param EventArgs $args
   */
  public function preUpdate(PreUpdateEventArgs $args) {
    $om = $this->getObjectManager($args);
    $object = $this->getObject($args);
    $meta = $om->getClassMetadata(get_class($object));

    if ($config = $this->getConfiguration($om, $meta->name)) {
      if ($args->hasChangedField($config['filename'])) {
        $oldFilename = $args->getOldValue($config['filename']);
        if (file_exists($config['path'] . '/' . $oldFilename)) {
          unlink($config['path'] . '/' . $oldFilename);
        }
        $this->refreshFile($object, $meta, $config);
      }
    }
  }

  /**
   * @param string $filename
   * @return string
   */
  private function detectMimeType($filename) {
    $result = null;
    
    if (class_exists('finfo', false)) {
      $const = defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME;
      $mime = @finfo_open($const);
      if (!empty($mime)) {
        $result = finfo_file($mime, $filename);
      }
      unset($mime);
    }

    if (empty($result) && (function_exists('mime_content_type') && ini_get('mime_magic.magicfile'))) {
      $result = mime_content_type($filename);
    }

    if (empty($result)) {
      $result = 'application/octet-stream';
    }
    return $result;
  }

  /**
   *
   * @param object $entity
   * @param Doctrine\ORM\Mapping\ClassMetadata $meta
   * @param array $config
   */
  private function refreshFile($entity, $meta, $config) {
    $filename = $meta->getReflectionProperty($config['filename'])->getValue($entity);
    if (array_key_exists('originalFilename', $config)) {
      $meta->getReflectionProperty($config['originalFilename'])->setValue($entity, basename($filename));
    }
    if (!file_exists($filename)) {
      throw new InvalidArgumentException("'$filename' have to be an existing file");
    }
    $extension = pathinfo($filename, \PATHINFO_EXTENSION);
    $newFilename = md5($filename . time() . mt_rand()) . '.' . $extension;
    $meta->getReflectionProperty($config['filename'])->setValue($entity, $newFilename);

    if (array_key_exists('size', $config)) {
      $meta->getReflectionProperty($config['size'])->setValue($entity, filesize($filename));
    }

    if (array_key_exists('md5Hash', $config)) {
      $meta->getReflectionProperty($config['md5Hash'])->setValue($entity, md5_file($filename));
    }

    if (array_key_exists('mimeType', $config)) {
      $meta->getReflectionProperty($config['mimeType'])->setValue($entity, $this->detectMimeType($filename));
    }

    $destination = $config['path'] . '/' . $newFilename;
    if ($config['method'] == 'move') {
      rename($filename, $destination);
    } else {
      copy($filename, $destination);
    }
  }

  /**
   * {@inheritDoc}
   */
  protected function getNamespace() {
    return __NAMESPACE__;
  }

  /**
   * Mapps additional metadata
   *
   * @param EventArgs $eventArgs
   * @return void
   */
  public function loadClassMetadata(EventArgs $eventArgs) {
    $this->loadMetadataForObjectClass($this->getObjectManager($eventArgs), $eventArgs->getClassMetadata());
  }

}