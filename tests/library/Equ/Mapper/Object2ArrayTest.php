<?php
namespace Equ\Mapper;

class InputObject {
  
  private $foo = 'foo';
  
  protected $bar = 'bar';
  
  public function getFoo() {
    return $this->foo;
  }
  
  public function getBar() {
    return $this->bar;
  }
  
}

class Object2ArrayTest extends \PHPUnit_Framework_TestCase {
  
  public function testConvert() {
    $mapper = new Object2Array(new InputObject());
    $values = $mapper->convert();
    self::assertArrayHasKey('foo', $values);
    self::assertArrayHasKey('bar', $values);
    self::assertEquals('foo', $values['foo']);
    self::assertEquals('bar', $values['bar']);
  }
  
  public function testConvertWithInitialArray() {
    $values = array('foo' => 'notFoo', 'else' => 'exists');
    $mapper = new Object2Array(new InputObject(), $values);
    $values = $mapper->convert();
    self::assertArrayHasKey('foo', $values);
    self::assertArrayHasKey('bar', $values);
    self::assertArrayHasKey('else', $values);
    self::assertEquals('foo', $values['foo']);
    self::assertEquals('bar', $values['bar']);
    self::assertEquals('exists', $values['else']);
  }
  
}
