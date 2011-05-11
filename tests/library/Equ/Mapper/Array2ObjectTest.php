<?php
namespace Equ\Mapper;

class OutputObject {
  
  private $foo;
  
  private $bar;
  
  public function setFoo($foo) {
    $this->foo = $foo;
  }

  public function setBar($bar) {
    $this->bar = $bar;
  }
  
  public function getFoo() {
    return $this->foo;
  }
  
  public function getBar() {
    return $this->bar;
  }
}

class Array2ObjectTest extends \PHPUnit_Framework_TestCase {
  
  public function testConvert() {
    $values = array('foo' => 'foo', 'bar' => 'bar');
    $output = new OutputObject();
    $converter = new Array2Object($values, $output);
    $converter->convert();
    self::assertEquals('foo', $output->getFoo());
    self::assertEquals('bar', $output->getBar());
  }
  
}