<?php

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class IndexPageTest extends PHPUnit_Extensions_SeleniumTestCase {

	function setUp() {
		parent::setUp();
		$this->setBrowserUrl( $GLOBALS[ 'browserUrl' ] );
	}
	
  function testMyTestCase() {
    $this->open("hu/");
    $this->type("login_name", "szjani");
    $this->type("login_password", "admin");
    $this->click("//span[@id='login']");
    $this->waitForCondition('"test@test.com" == selenium.getText("loggedUserEmail")', 3000);
    $this->assertTrue(true);
  }
}
