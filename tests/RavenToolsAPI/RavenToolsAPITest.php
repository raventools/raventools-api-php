<?php

class RavenToolsAPITest extends PHPUnit_Framework_TestCase {
	
	public function setup() {
		if (defined('USE_MOCK_TRANSPORT') && USE_MOCK_TRANSPORT == true) {
			$transport = new RavenToolsAPITransportMock();
		} else {
			$transport = null;
		}
		$this->object = new RavenToolsAPI(RAVEN_API_KEY, $transport);
	}
	
	public function teardown() {
	}
	
	public function testConnectionFailure() {
		$this->setExpectedException('RavenToolsAPIException');
		$this->object = new RavenToolsAPI('bogusstring');
		$result = $this->object->getProfileInfo();
	}
	
	public function testGetProfileInfo() {
		$result = $this->object->getProfileInfo();
		$this->assertInternalType('object', $result);
		$this->assertObjectHasAttribute('name', $result);
		$this->assertObjectHasAttribute('keyword_usage', $result);
	}

	public function testGetDomains() {
		$result = $this->object->getDomains();
		$this->assertInternalType('array', $result);
		$this->assertGreaterThan(1, count($result));
	}

	public function testAddDomain() {
		$domain = uniqid() . '.example.com';
		$result = $this->object->addDomain($domain, array(1));
		$this->assertEquals('success', $result->response);
	}

	public function testRemoveDomain() {
		$domain = uniqid() . '.example.com';
		$this->object->addDomain($domain, 1);
		$this->object->removeDomain($domain);
		$domains = $this->object->getDomains();
		$this->assertFalse(in_array($domain, $domains));
	}

	public function	testGetEngines() {
		$result = $this->object->getEngines();
		$this->assertInternalType('array', $result);
		$this->assertGreaterThan(20, count($result));
		$this->assertEquals('Yahoo! GR', $result[0]->name);
	}

	public function	testGetDomainInfo() {
		$domains = $this->object->getDomains();
		$result = $this->object->getDomainInfo($domains[0]);
		$this->assertInternalType('array', $result);
	}

	public function testGetRank() {
		$domains = $this->object->getDomains();
		$keywords = $this->object->getKeywords( $domains[0] );
		if (!isset($keywords[0])) {
			$this->markTestSkipped('No keywords for given domain: ' . $domains[0]);
		}
		$maxWeek = $this->object->getRankMaxWeek( $domains[0], $keywords[0] );
		if ($maxWeek->date == '') {
			$this->markTestSkipped('No keyword history for given domain: ' . $domains[0]);
		}
		$start_date = new DateTime($maxWeek->date);
		$start_date->sub(new DateInterval('P30D'));
		$start_date = $start_date->format('Y-m-d');
		$end_date = $maxWeek->date;
		$result = $this->object->getRank( $domains[0], $keywords[0], $start_date, $end_date, 'all' );
		if (empty($result)) {
			$this->markTestSkipped('No keyword history for given domain: ' . $domains[0]);
		}
		$this->assertGreaterThan(0, count(get_object_vars($result)));
		$engine = key(get_object_vars($result));
		$this->assertObjectHasAttribute('date', $result->{$engine}[0]);
		$this->assertObjectHasAttribute('status', $result->{$engine}[0]);
	}

	public function testGetRankAll() {
		$domains = $this->object->getDomains();
		$keywords = $this->object->getKeywords( $domains[0] );
		if (!isset($keywords[0])) {
			$this->markTestSkipped('No keywords for given domain: ' . $domains[0]);
		}
		$maxWeek = $this->object->getRankMaxWeek( $domains[0], $keywords[0] );
		if ($maxWeek->date == '') {
			$this->markTestSkipped('No keyword history for given domain: ' . $domains[0]);
		}
		$start_date = new DateTime($maxWeek->date);
		$start_date->sub(new DateInterval('P30D'));
		$start_date = $start_date->format('Y-m-d');
		$end_date = $maxWeek->date;
		$result = $this->object->getRankAll( $domains[0], $start_date, $end_date );
		if (empty($result)) {
			$this->markTestSkipped('No keyword history for given domain: ' . $domains[0]);
		}
		$this->assertGreaterThan(0, count(get_object_vars($result)));
		$keyword = key(get_object_vars($result));
		$engine = key(get_object_vars($result->{$keyword}));
		$this->assertObjectHasAttribute('date', $result->{$keyword}->{$engine}[0]);
		$this->assertObjectHasAttribute('status', $result->{$keyword}->{$engine}[0]);
	}

	public function testGetRankMaxWeek() {
		$domains = $this->object->getDomains();
		$keywords = $this->object->getKeywords( $domains[0] );
		if (!isset($keywords[0])) {
			$this->markTestSkipped('No keywords for given domain: ' . $domains[0]);
		}
		$result = $this->object->getRankMaxWeek( $domains[0], $keywords[0] );
		$this->assertObjectHasAttribute('keyword', $result);
		$this->assertNotNull($result->keyword);
		$this->assertObjectHasAttribute('domain', $result);
		$this->assertNotNull($result->domain);
		if (isset($result->status)) {
			$this->assertObjectHasAttribute('status', $result);
			$this->assertNotNull($result->status);
		} else {
			$this->assertObjectHasAttribute('week', $result);
			$this->assertNotNull($result->week);
		}
		$this->assertObjectHasAttribute('date', $result);
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

	public function testGetKeywords() {
		$domains = $this->object->getDomains();
		$result = $this->object->getKeywords($domains[0]);
		$this->assertInternalType('array', $result);
		if (count($result) < 1) {
			$this->markTestSkipped('Cannot test getKeywords with less than one record.');
		}
		$this->assertNotNull($result[0]);
	}
	
	public function testGetKeywordsTags() {
		$domains = $this->object->getDomains();
		$result = $this->object->getKeywordsTags($domains[0]);
		$this->assertInternalType('array', $result);
		if (count($result) < 1) {
			$this->markTestSkipped('Cannot test getKeywords with less than one record.');
		}
		$this->assertNotNull($result[0]->keyword);
		$this->assertNotNull($result[0]->tags);
	}

	public function testAddKeyword() {
		$domains = $this->object->getDomains();
		$keyword = uniqid();
		$result = $this->object->addKeyword($domains[0], $keyword);
		$this->assertObjectHasAttribute('response', $result);
		$this->assertEquals('success', $result->response);
		$this->object->removeKeyword($domains[0], $keyword);
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

	public function testGetLinks() {
		$domains = $this->object->getDomains();
		$result = $this->object->getLinks($domains[0]);
		$this->assertInternalType('array', $result);
		if (count($result) < 1) {
			$this->markTestSkipped('Cannot test getLinks with less than one record.');
		}
		$this->assertObjectHasAttribute('link_text', $result[0]);
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
		$result = $this->object->get('engines');
		$this->assertInternalType('array', $result);
		$this->object->format = 'xml';
		$result = $this->object->get('domains');
		$this->assertInternalType('object', $result->domains->domain);
	}
	
}