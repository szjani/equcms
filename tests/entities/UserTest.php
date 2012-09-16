<?php
namespace entities;

use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testAssertTrue()
    {
        $user = new User('asd@dfg.com', 'pass');
        self::assertEquals('asd@dfg.com', $user->getEmail());
    }
}