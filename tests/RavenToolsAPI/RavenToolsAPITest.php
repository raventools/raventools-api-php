<?php

class RavenToolsAPITest extends PHPUnit_Framework_TestCase {

	public function setup() {
		if (defined('USE_MOCK_TRANSPORT') && USE_MOCK_TRANSPORT == true) {
			$this->transport = new \RavenTools\RavenToolsAPITransportMock();
		} else {
			$this->transport = null;
		}
		$this->object = new \RavenTools\RavenToolsAPI(RAVEN_API_KEY, $this->transport);
	}

	public function teardown() {
	}

	public function testConnectionFailure() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$this->object = new \RavenTools\RavenToolsAPI('bogusstring');
		$result = $this->object->getDomains();
	}

	public function testGetProfile() {
		$result = $this->object->getProfile();
		$this->assertNotNull($result->name);
	}

	public function testGetDomain() {
		$domains = $this->object->getDomains();
		$result = $this->object->getDomain($domains[0]);
		$this->assertInternalType('object', $result);
		$this->assertNotNull($result->domain);
		$this->assertNotNull($result->description);
	}

	public function testGetDomainException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->getDomain();
	}

	public function testGetDomains() {
		$result = $this->object->getDomains();
		$this->assertInternalType('array', $result);
		$this->assertGreaterThan(1, count($result));
	}

	public function testAddDomain() {
		$domain = uniqid() . '.example.com';
		$result = $this->object->addDomain($domain);
		$this->assertEquals('success', $result->response);
		$this->object->removeDomain($domain);
	}

	public function testAddDomainException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->addDomain();
	}

	public function testRemoveDomain() {
		$domain = uniqid() . '.example.com';
		$this->object->addDomain($domain, 1);
		$this->object->removeDomain($domain);
		$domains = $this->object->getDomains();
		$this->assertFalse(in_array($domain, $domains));
	}

	public function testRemoveDomainException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->removeDomain();
	}

	public function testGetCompetitors() {
		$domains = $this->object->getDomains();
		$result = $this->object->getCompetitors($domains[0]);
		$this->assertInternalType('array', $result);
		if (count($result) < 1) {
			$this->markTestSkipped('Cannot test getCompetitors with less than one record.');
		}
		$this->assertInternalType('string', $result[0]);
	}

	public function testGetCompetitorsException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->getCompetitors();
	}

	public function testGetKeywords() {
		$domains = $this->object->getDomains();
		$result = $this->object->getKeywords($domains[0]);
		$this->assertInternalType('array', $result);
		if (count($result) < 1) {
			$this->markTestSkipped('Cannot test getKeywords with less than one record.');
		}
		$this->assertNotNull($result[0]);
	}

	public function testGetKeywordsException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->getKeywords();
	}

	public function testGetKeywordsTags() {
		$domains = $this->object->getDomains();
		$result = $this->object->getKeywordsTags($domains[0]);
		$this->assertInternalType('array', $result);
		if (count($result) < 1) {
			$this->markTestSkipped('Cannot test getKeywords with less than one record.');
		}
		$this->assertObjectHasAttribute('keyword', $result[0]);
		$this->assertObjectHasAttribute('tags', $result[0]);
	}

	public function testGetKeywordsTagsException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->getKeywordsTags();
	}

	public function testAddKeyword() {
		$domains = $this->object->getDomains();
		$keyword = uniqid();
		$result = $this->object->addKeyword($domains[0], $keyword);
		$this->assertObjectHasAttribute('response', $result);
		$this->assertEquals('success', $result->response);
		$this->object->removeKeyword($domains[0], $keyword);
	}

	public function testAddKeywordException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->addKeyword();
	}

	public function testRemoveKeyword() {
		$domains = $this->object->getDomains();
		$keyword = uniqid();
		$this->object->addKeyword($domains[0], $keyword);
		$result = $this->object->removeKeyword($domains[0], $keyword);
		$this->assertObjectHasAttribute('response', $result);
		$this->assertEquals('success', $result->response);
		$this->object->removeKeyword($domains[0], $keyword);
	}

	public function testRemoveKeywordException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->removeKeyword();
	}

	public function testGetLinks() {
		$domains = $this->object->getDomains();
		$result = $this->object->getLinks($domains[0]);
		$this->assertInternalType('array', $result);
		if (count($result) < 1) {
			$this->markTestSkipped('Cannot test getLinks with less than one record.');
		}
		$this->assertObjectHasAttribute('link_text', $result[0]);
	}

	public function testGetLinksException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->getLinks();
	}

	public function testAddLinks() {
		if (!defined('USE_MOCK_TRANSPORT') || USE_MOCK_TRANSPORT != true) {
			$this->markTestSkipped('Link add/update/delete tests not implemented for live connection.');
		}
		$domains = $this->object->getDomains();
		$links = '[{"domain":"raventools.com","status":"active","link text":"Raven Blog","link url":"http://www.raventools.com/blog","link description":"Raven Tools Blog"}]';
		$result = $this->object->addLinks($domains[0], $links);
		$this->assertEquals(3, count($result));
		$this->assertTrue(in_array('5760823', $result));
	}

	public function testAddLinksException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->addLinks();
	}

	public function testUpdateLinks() {
		if (!defined('USE_MOCK_TRANSPORT') || USE_MOCK_TRANSPORT != true) {
			$this->markTestSkipped('Link add/update/delete tests not implemented for live connection.');
		}
		$domains = $this->object->getDomains();
		$links = '[{"link id":"130","status":"active","link text":"Raven Blog","link url":"www.raventools.com/blog","link type":"Paid (Permanent)","link description":"Raven Tools Blog","website type":"Social Media","website url":"www.about.com","tags":"raven,blog","creation date":"2012-07-14","paymentmethod":"paypal","cost":"12.45"}]';
		$result = $this->object->updateLinks($domains[0], $links);
		$this->assertEquals(3, count(get_object_vars($result)));
		$this->assertTrue( (boolean) $result->{'5760823'});
	}

	public function testUpdateLinksException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->updateLinks();
	}

	public function testDeleteLinks() {
		if (!defined('USE_MOCK_TRANSPORT') || USE_MOCK_TRANSPORT != true) {
			$this->markTestSkipped('Link add/update/delete tests not implemented for live connection.');
		}
		$domains = $this->object->getDomains();
		$links = array( array('link_id' => 1), array('link_id' => 130),  array('link_id' => 131), array('link_id' => 132) );
		$result = $this->object->deleteLinks($domains[0], $links);
		$this->assertEquals(3, count(get_object_vars($result)));
		$this->assertTrue( (boolean) $result->{'130'});
	}

	public function testDeleteLinksException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->deleteLinks();
	}

	public function testGetWebsiteTypes() {
		$result = $this->object->getWebsiteTypes();
		$this->assertInternalType('object', $result);
		$this->assertInternalType('array', $result->results);
		$this->assertGreaterThan(1, count($result->results));
	}

	public function testGetLinkTypes() {
		$result = $this->object->getLinkTypes();
		$this->assertInternalType('object', $result);
		$this->assertInternalType('array', $result->results);
		$this->assertGreaterThan(1, count($result->results));
	}

	public function testGetJSON() {
		$result = $this->object->getJSON('domains');
		$this->assertTrue( (bool) json_decode($result));
		$this->assertInternalType('array', json_decode($result));
	}

	public function testGetXML() {
		$result = $this->object->getXML('domains');
		$actual = new DOMDocument;
		$actual->loadXML($result);
		$this->assertContains('<?xml version="1.0" encoding="UTF-8"?>', $result);
		$this->assertContains('<Raven>', $result);
		$this->assertContains('<domains>', $result);
		$this->assertContains('<domain>', $result);
		$this->assertSelectCount('domains domain', true, $actual);
	}

	public function testGet() {
		$result = $this->object->get('domains');
		$this->assertInternalType('array', $result);
		$this->object->format = 'xml';
		$result = $this->object->get('domains');
		$this->assertInternalType('object', $result->domains->domain);
	}

	public function testValidateAPIKey() {
		$result = $this->object->validateAPIKey(RAVEN_API_KEY, $this->transport);
		$this->assertTrue($result);
	}

	public function testValidateAPIKeyException() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$result = $this->object->validateAPIKey('bogus');
	}

	public function testSetMethod() {
		$this->setExpectedException('\RavenTools\RavenToolsAPIException');
		$this->object->getJSON('foobar');
	}

}
