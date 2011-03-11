<?php

namespace Equ\Doctrine\FileStore\Mapping\Driver;

use Gedmo\Mapping\Driver,
 Doctrine\Common\Annotations\AnnotationReader,
 Doctrine\Common\Persistence\Mapping\ClassMetadata,
 Gedmo\Exception\InvalidMappingException;

class Annotation implements Driver {
  const ANNOTATION_FILESTORE = 'Equ\Doctrine\FileStore\Mapping\FileStore';
  const ANNOTATION_FILENAME = 'Equ\Doctrine\FileStore\Mapping\Filename';
  const ANNOTATION_ORIGINAL_FILENAME = 'Equ\Doctrine\FileStore\Mapping\OriginalFilename';
  const ANNOTATION_SIZE = 'Equ\Doctrine\FileStore\Mapping\Size';
  const ANNOTATION_MD5HASH = 'Equ\Doctrine\FileStore\Mapping\Md5Hash';
  const ANNOTATION_MIMETYPE = 'Equ\Doctrine\FileStore\Mapping\MimeType';

  private $validStringTypes = array(
    'string',
  );
  private $validNumericTypes = array(
    'integer',
    'smallint',
    'bigint',
  );

  /**
   * Checks if $field type is valid
   *
   * @param ClassMetadata $meta
   * @param string $field
   * @return boolean
   */
  protected function isValidField(ClassMetadata $meta, $field, $types) {
    $mapping = $meta->getFieldMapping($field);
    return $mapping && in_array($mapping['type'], $types);
  }

  /**
   * @param ClassMetadata $meta
   * @param array $config
   */
  public function readExtendedMetadata(ClassMetadata $meta, array &$config) {
    require_once __DIR__ . '/../Annotations.php';
    $reader = new AnnotationReader();
    $reader->setAnnotationNamespaceAlias('Equ\Doctrine\FileStore\Mapping\\', 'equ');

    $class = $meta->getReflectionClass();
    // class annotations
    $classAnnotations = $reader->getClassAnnotations($class);
    if (isset($classAnnotations[self::ANNOTATION_FILESTORE])) {
      $annot = $classAnnotations[self::ANNOTATION_FILESTORE];
      if (!file_exists($annot->path) || !is_writable($annot->path) || !is_dir($annot->path)) {
        throw new InvalidMappingException("Directory '{$annot->path}' has be a writtable directory!");
      }
      $config['path'] = rtrim($annot->path, '/');
      
      if (!\in_array($annot->method, array('move', 'copy'))) {
        throw new InvalidMappingException("Method '{$annot->method}' has to be 'move' or 'copy'!");
      }
      $config['method'] = $annot->method;
    }

    // property annotations
    foreach ($class->getProperties() as $property) {
      if ($meta->isMappedSuperclass && !$property->isPrivate() ||
        $meta->isInheritedField($property->name) ||
        isset($meta->associationMappings[$property->name]['inherited'])
      ) {
        continue;
      }
      // filename
      if ($filename = $reader->getPropertyAnnotation($property, self::ANNOTATION_FILENAME)) {
        $field = $property->getName();
        if (!$meta->hasField($field)) {
          throw new InvalidMappingException("Unable to find 'filename' - [{$field}] as mapped property in entity - {$meta->name}");
        }
        if (!$this->isValidField($meta, $field, $this->validStringTypes)) {
          throw new InvalidMappingException("FileStore filename field - [{$field}] type is not valid and must be 'string' in class - {$meta->name}");
        }
        $config['filename'] = $field;
      }
      // originalFilename
      if ($originalFilename = $reader->getPropertyAnnotation($property, self::ANNOTATION_ORIGINAL_FILENAME)) {
        $field = $property->getName();
        if (!$meta->hasField($field)) {
          throw new InvalidMappingException("Unable to find 'originalFilename' - [{$field}] as mapped property in entity - {$meta->name}");
        }
        if (!$this->isValidField($meta, $field, $this->validStringTypes)) {
          throw new InvalidMappingException("FileStore originalFilename field - [{$field}] type is not valid and must be 'string' in class - {$meta->name}");
        }
        $config['originalFilename'] = $field;
      }
      // size
      if ($size = $reader->getPropertyAnnotation($property, self::ANNOTATION_SIZE)) {
        $field = $property->getName();
        if (!$this->isValidField($meta, $field, $this->validNumericTypes)) {
          throw new InvalidMappingException("FileStore size field - [{$field}] type is not valid and must be 'integer' in class - {$meta->name}");
        }
        $config['size'] = $field;
      }
      // md5Hash
      if ($md5Hash = $reader->getPropertyAnnotation($property, self::ANNOTATION_MD5HASH)) {
        $field = $property->getName();
        if (!$meta->hasField($field)) {
          throw new InvalidMappingException("Unable to find 'md5Hash' - [{$field}] as mapped property in entity - {$meta->name}");
        }
        if (!$this->isValidField($meta, $field, $this->validStringTypes)) {
          throw new InvalidMappingException("FileStore md5Hash field - [{$field}] type is not valid and must be 'string' in class - {$meta->name}");
        }
        $config['md5Hash'] = $field;
      }
      // mimetype
      if ($mimeType = $reader->getPropertyAnnotation($property, self::ANNOTATION_MIMETYPE)) {
        $field = $property->getName();
        if (!$meta->hasField($field)) {
          throw new InvalidMappingException("Unable to find 'mimeType' - [{$field}] as mapped property in entity - {$meta->name}");
        }
        if (!$this->isValidField($meta, $field, $this->validStringTypes)) {
          throw new InvalidMappingException("FileStore mimeType field - [{$field}] type is not valid and must be 'string' in class - {$meta->name}");
        }
        $config['mimeType'] = $field;
      }
    }
  }

  /**
   * @param ClassMetadata $meta
   * @param array $config
   */
  public function validateFullMetadata(ClassMetadata $meta, array $config) {
    $missingFields = array();
    if (!isset($config['filename'])) {
      $missingFields[] = 'filename';
    }
    if ($missingFields) {
      throw new InvalidMappingException("Missing properties: " . implode(', ', $missingFields) . " in class - {$meta->name}");
    }
  }

}