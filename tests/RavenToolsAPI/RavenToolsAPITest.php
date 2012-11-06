<?php

require_once '../src/class.raven-api-php.php';

class RavenToolsAPITest extends PHPUnit_Framework_TestCase {
	
	public function setup() {
		$this->object = new RavenToolsAPI(RAVEN_API_KEY);
	}
	
	public function teardown() {
	}
	
	/**
	 * @expectedException RavenToolsAPIException
	 */
	public function testConnectionFailure() {
		$this->object = new RavenToolsAPI('bogusstring');
		$return = $this->object->getProfileInfo();
	}
	
	public function testGetProfileInfo() {
		$return = $this->object->getProfileInfo();
		$this->assertInternalType('object', $return);
		$this->assertObjectHasAttribute('name', $return);
		$this->assertObjectHasAttribute('keyword_usage', $return);
	}

	public function testGetDomains() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testAddDomain() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testRemoveDomain() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function	testGetEingines() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function	testGetDomainInfo() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetRank() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetRankAll() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetRankMaxWeek() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetCompetitors() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetKeywords() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testAddKeyword() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testRemoveKeyword() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetLinks() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetJSON() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetXML() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGet() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testValidateAPIKey() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testSetMethod() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testGetResponse() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testCheckRequired() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testBuildRequestURL() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testCurl() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}

	public function testParseResponse() {
		$this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
	}
	
}