<?php
namespace Equ\Doctrine\FileStore\Mapping;

use Doctrine\Common\Annotations\Annotation;

final class FileStore extends Annotation {

  /**
   * Directory path
   *
   * @var string
   */
  public $path;

  /**
   * move|copy
   * 
   * @var string
   */
  public $method;

}
final class Filename extends Annotation {}
final class OriginalFilename extends Annotation {}
final class Size extends Annotation {}
final class Md5Hash extends Annotation {}
final class MimeType extends Annotation {}