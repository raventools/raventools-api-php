<?php

class RavenToolsAPITransportMock extends RavenToolsAPITransport {
	
	public function curl($url, array $get = array(), array $options = array()) {
		$url_parsed = parse_url($url);
		parse_str($url_parsed['query'], $query);
		
		$contents = $this->loadFixture($query['method'] . '.' . $query['format']);
		
		return $contents;
		
	}
	
	private function loadFixture($file = '') {
		$path = dirname(__FILE__) . '/fixtures/' . $file;
		if (!file_exists($path)) {
			return null;
		}
		
		$contents = file_get_contents($path);
		return $contents;
	}
	
}