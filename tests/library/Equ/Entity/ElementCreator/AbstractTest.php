<?php
namespace Equ\Entity\ElementCreator;

class AbstractTest extends \PHPUnit_Framework_TestCase {

  /**
   *
   * @var \Equ\Entity\ElementCreator\AbstractCreator
   */
  private $elementCreator;

  public function setUp() {
    $this->elementCreator = $this->getMock('Equ\Entity\ElementCreator\AbstractCreator', array('buildElement'));
  }

  /**
   *
   * @return \Equ\Entity\ElementCreator\AbstractCreator
   */
  private function getElementCreator() {
    return $this->elementCreator;
  }

  public function testAddFlag() {
    $creator = $this->getElementCreator();
    $creator->addFlag(AbstractCreator::IMPLICIT_VALIDATORS);
    self::assertTrue($creator->hasFlag(AbstractCreator::IMPLICIT_VALIDATORS));
    self::assertFalse($creator->hasFlag(AbstractCreator::EXPLICIT_VALIDATORS));
    $creator->addFlag(AbstractCreator::EXPLICIT_VALIDATORS);
    self::assertTrue($creator->hasFlag(AbstractCreator::IMPLICIT_VALIDATORS));
    self::assertTrue($creator->hasFlag(AbstractCreator::EXPLICIT_VALIDATORS));
    return $creator;
  }

  /**
   * @depends testAddFlag
   * @param AbstractCreator $creator
   */
  public function testRemoveFlag(AbstractCreator $creator) {
    $creator->removeFlag(AbstractCreator::EXPLICIT_VALIDATORS);
    self::assertFalse($creator->hasFlag(AbstractCreator::EXPLICIT_VALIDATORS));
  }

}