<?php
namespace Equ;

class DTOTest extends \PHPUnit_Framework_TestCase {

  private function getTestArray() {
    return array(
      'hello',
      5,
      'test' => 'testString'
    );
  }

  private function getTestArrayObject() {
    return new \ArrayObject($this->getTestArray());
  }

  public function testFromArray() {
    $dto = new DTO();
    $dto->fromArray($this->getTestArray());
  }

  public function testFromTraversable() {
    $dto = new DTO();
    $dto->fromTraversable($this->getTestArrayObject());
    self::assertEquals(3, $dto->count());
    self::assertTrue($dto->hasData('test'));
    return $dto;
  }

  /**
   * @depends testFromTraversable
   * @param DTO $dto
   */
  public function testGetIterator(DTO $dto) {
    $i = 0;
    foreach ($dto->getIterator() as $key => $value) {
      switch ($i++) {
        case 0:
          self::assertEquals(0, $key);
          self::assertEquals('hello', $value);
          break;
        case 1:
          self::assertEquals(1, $key);
          self::assertEquals(5, $value);
          break;
        case 2:
          self::assertEquals('test', $key);
          self::assertEquals('testString', $value);
          break;
        default:
          self::fail('More items than expected');
          break;
      }
    }
  }

  /**
   * @depends testFromTraversable
   * @param DTO $dto
   */
  public function testArrayAccess(DTO $dto) {
    self::assertEquals('testString', $dto['test']);
    self::assertNull($dto['invalid']);
  }

}