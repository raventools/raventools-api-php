<?php

/**
 * Raven Tools API PHP Class
 *
 * @link https://github.com/stephenyeargin/raventools-api-php
 * @package default
 * @version 1.1
 */
class RavenToolsAPI {

  const api_version = '1.0';
  const end_point = 'https://api.raventools.com/api';

  private $api_key;

  public $method;
  public $domain;
  public $start_date;
  public $end_date;
  public $engine;
  public $keyword;
  public $format;
  public $request;
  public $response;
  public $required_fields;
  public $optional_fields;

  private $transport;

  public function __construct($api_key = null, $transport = null) {
    $this->api_key = $api_key;
    $this->format = 'json';
    $this->required_fields = array();
    $this->optional_fields = array();

    if (is_null($transport) || !$transport) {
      $this->transport = new RavenToolsAPITransport();
    } else {
      $this->transport = $transport;
    }

  }

  public function __set($name, $value) {
    $this->$name = $value;
  }

  public function __get($name) {
    return $this->$name;
  }

  /**
   * Get Profile Info
   *
   * @return object
   */
  public function getProfileInfo() {
    return $this->get('profile_info');
  }

  /**
   * Get Domains
   *
   * @return object
   */
  public function getDomains() {
    return $this->get('domains');
  }

  /**
   * Add Domain
   *
   * @param string $domain
   * @param array $engines
   */
  public function addDomain( $domain = '', $engines = array() ) {
    if (!isset($domain) || empty($domain)):
      throw new RavenToolsAPIException("The domain or engine was not set as part of this request. Required by addDomain().");
    endif;

    if (is_array($engines) && !empty($engines)):
      $engines = implode(',', $engines);
    endif;

    return $this->get('add_domain', array('domain'=>$domain,'engine_id'=>$engines) );
  }

  /**
   * Remove Domain
   *
   * @param string $domain
   */
  public function removeDomain ( $domain = '' ) {
    if (!isset($domain) || empty($domain)):
      throw new RavenToolsAPIException("The domain was not set as part of this request. Required by removeDomain().");
    endif;

    return $this->get('remove_domain', array('domain'=>$domain) );
  }

  /**
   * Get Engines
   *
   * @return object
   */
  public function getEngines() {
    return $this->get('engines');
  }

  /**
   * Get Domain Info
   *
   * @param string $domain
   * @return object
   */
  public function getDomainInfo( $domain = '' ) {
    if ( empty($domain) ):
      throw new RavenToolsAPIException("The domain was not set as part of this request. Required by getDomainInfo().");
    endif;

    return $this->get('domain_info', array('domain'=>$domain) );
  }

  /**
   * Get Rank
   *
   * @param string $keyword
   * @param string $domain
   * @param string $start_date
   * @param string $end_date
   * @param string $engine
   * @return object
   */
  public function getRank( $domain = '', $keyword = '', $start_date = '', $end_date = '', $engine = 'all' ) {
    if ( empty($domain) || empty($keyword) || empty($start_date) || empty($end_date) || empty($engine) ):
      throw new RavenToolsAPIException("The domain, keyword, start date, end date or engine was not set as part of this request. Required by getRank().");
    endif;

    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));

    return $this->get('rank', array('keyword'=>$keyword,'domain'=>$domain,'start_date'=>$start_date,'end_date'=>$end_date,'engine'=>$engine) );
  }

  /**
   * Get Ranking for All Keywords
   *
   * @param string $domain
   * @param string $start_date
   * @param string $end_date
   * @return object
   */
  public function getRankAll( $domain = '', $start_date = '', $end_date = '' ) {
    if ( empty($domain) || empty($start_date) || empty($end_date) ):
      throw new RavenToolsAPIException("The domain, start date or end date was not set as part of this request. Required by getRankAll().");
    endif;

    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));

    return $this->get('rank_all', array('domain'=>$domain,'start_date'=>$start_date,'end_date'=>$end_date) );
  }

  /**
   * Get Ranking Max for a Week
   *
   * @param string $domain
   * @param string $keyword
   * @return object
   */
  public function getRankMaxWeek( $domain = '', $keyword = '' ) {
    if ( empty($domain) || empty($keyword) ):
      throw new RavenToolsAPIException("The domain or keyword was not set as part of this request. Required by getRankMaxWeek().");
    endif;

    return $this->get('rank_max_week', array('domain'=>$domain,'keyword'=>$keyword) );
  }

  /**
   * Get Competitors
   *
   * @param string $domain
   * @return object
   */
  public function getCompetitors( $domain = '' ) {
    if ( empty($domain) ):
      throw new RavenToolsAPIException("The domain was not set as part of this request. Required by getCompetitors().");
    endif;

    return $this->get('competitors', array('domain'=>$domain) );
  }

  /**
   * Get Keywords
   *
   * @param string $domain
   * @return object
   */
  public function getKeywords( $domain = '' ) {
    if ( empty($domain) ):
      throw new RavenToolsAPIException("The domain was not set as part of this request. Required by getKeywords().");
    endif;

    return $this->get('keywords', array('domain'=>$domain) );
  }

  public function getKeywordsTags( $domain = '' ) {
    if ( empty($domain) ):
      throw new RavenToolsAPIException("The domain was not set as part of this request. Required by getKeywordsTags().");
    endif;

    return $this->get('keywords_tags', array('domain'=>$domain) );
  }

  /**
   * Add Keyword
   *
   * @param string $keyword
   * @param string $domain
   * @return object
   */
  public function addKeyword( $domain = '', $keyword = '' ) {
    if ( empty($domain) || empty($keyword) ):
      throw new RavenToolsAPIException("The domain or keyword was not set as part of this request. Required by addKeyword().");
    endif;

    return $this->get('add_keyword', array('domain'=>$domain,'keyword'=>$keyword) );
  }

  /**
   * Remove Keyword
   *
   * @param string $keyword
   * @param string $domain
   * @return object
   */
  public function removeKeyword( $domain = '', $keyword = '' ) {
    if ( empty($domain) || empty($keyword) ):
      throw new RavenToolsAPIException("The domain or keyword was not set as part of this request. Required by removeKeyword().");
    endif;

    return $this->get('remove_keyword', array('domain'=>$domain,'keyword'=>$keyword) );
  }

  /**
   * Get Links
   *
   * @param string $domain
   * @return object
   */
  public function getLinks( $domain = '' ) {
    if ( empty($domain) ):
      throw new RavenToolsAPIException("The domain was not set as part of this request. Required by getLinks().");
    endif;

    return $this->get('get_links', array('domain'=>$domain) );
  }

  /**
   * Get Website Types
   *
   * @return object
   */
  public function getWebsiteTypes() {
    return $this->get('get_website_types');
  }

  /**
   * Get Link Types
   *
   * @return object
   */
  public function getLinkTypes() {
    return $this->get('get_link_types');
  }

  /* Core query methods */

  /**
   * Get JSON
   *
   * @param string $method - defines specific method to query (part of query string, sets required fields)
   * @param array $options - defines options to be passed to query string
   * @return string - JSON response
   */
  public function getJSON($method, $options = array()) {
    $this->format = 'json';
    $this->setMethod($method);
    $response = $this->get_response($options);

    return (string) $response;
  }

  /**
   * Get XML
   *
   * @param string $method - defines specific method to query (part of query string, sets required fields)
   * @param array $options - defines options to be passed to query string
   * @return string - XML response
   */
  public function getXML($method, $options = array()) {
    $this->format = 'xml';
    $this->setMethod($method);
    $response = $this->get_response($options);

    return (string) $response;
  }

  /**
   * Get PHP Object
   *
   * @param string $method - defines specific method to query (part of query string, sets required fields)
   * @param array $options - defines options to be passed to query string
   * @return object - decoded JSON response
   */
  public function get($method, $options = array()) {
    $this->setMethod($method);
    if ($this->format == 'json') {
      $response = $this->getJSON($method, $options);
file_put_contents( '/tmp/ravenapi-' . $method . time(), $response);

      return json_decode($response);
    } else {
      $response = $this->getXML($method, $options);

      return new SimpleXMLElement($response);
    }
  }

  /* Static methods */

  /**
   * Validate API Key
   *
   * @param string $key - Key to be validated
   * @return boolean - true upon success, false if no response
   */
  public static function validateAPIKey($key) {
    $testing = new self($key);
    try {
      $result = $testing->get('domains');
    } catch (Exception $e) {
      return false;
    }
    if (is_array($result)):
      return true;
    else:
      return false;
    endif;
  }

  /* Private methods */

  /**
   * Set Method
   *
   * Sets the method for a particular request and defines the required and optional fields for each.
   *
   * @param string $method - One of the available objects from the Raven API
   */
  private function setMethod($method) {

    $this->method = $method;

    switch ($method):

      case 'rank':
        $this->required_fields = array('domain', 'keyword', 'start_date', 'end_date', 'engine');
      break;

      case 'rank_all':
        $this->required_fields = array('domain', 'start_date');
      break;

      case 'domains':
        $this->required_fields = array();
      break;

      case 'add_domain':
        $this->required_fields = array('domain', 'engine_id');
      break;

      case 'remove_domain':
        $this->required_fields = array('domain');
      break;

      case 'rank_max_week':
        $this->required_fields = array('domain');
        $this->optional_fields = array('keyword');
      break;

      case 'engines':
        $this->required_fields = array();
      break;

      case 'profile_info':
        $this->required_fields = array();
      break;

      case 'domain_info':
        $this->required_fields = array('domain');
      break;

      case 'competitors':
        $this->required_fields = array('domain');
      break;

      case 'keywords':
        $this->required_fields = array('domain');
      break;

      case 'keywords_tags':
        $this->required_fields = array('domain');
      break;

      case 'add_keyword':
        $this->required_fields = array('keyword', 'domain');
      break;

      case 'remove_keyword':
        $this->required_fields = array('keyword', 'domain');
      break;

      case 'get_links':
        $this->required_fields = array('domain');
        $this->optional_fields = array('tag');
      break;

      case 'get_website_types':
        $this->required_fields = array();
      break;

      case 'get_link_types':
        $this->required_fields = array();
      break;

      default:
        throw new RavenToolsAPIException("'{$method}' was not recognized as a valid method.", 400);
        break;

    endswitch;
  }

  /**
   * Get Response
   *
   * Primary processing method. Makes the call to build the URL, error checks and processes the cURL request.
   *
   * @param string $options - Options passed from get(), getJSON() or getXML()
   * @return object/string/boolean - Response from query or false
   */
  private function get_response($options = array()) {
    $url = $this->build_request_url($options);
    $response = $this->transport->curl($url);

    return $this->parse_response($response);
  }

  /**
   * Check Required Fields
   *
   * Iterates through the 'required_fields' property array to ensure that all necessary properties are set prior to a request being made.
   *
   * @return object
   */
  private function check_required() {
    foreach ($this->required_fields as $field) {
      if (!isset($this->$field) || empty($this->$field)):
        throw new RavenToolsAPIException("The '{$field}' was not set as part of this request. Required by '{$this->method}' method.", 400);
      endif;
    }
  }

  /**
   * Build Request URL
   *
   * Constructs the URL to send based on the options passed in. Only uses those in the required_fields and optional_fields properties.
   *
   * @param array $options - An array of options passed from get(), getJSON() or getXML()
   * @return string - URL to be requested
   */
  private function build_request_url($options = array()) {

    // Take the options array and set the properties
    foreach ($options as $key => $value):
      $this->$key = $value;
    endforeach;

    // Verify that every required attribute was specified, throw exception if not
    $this->check_required();

    // Begin building the URL for the request
    $url = self::end_point . '?key=' . $this->api_key . '&method=' . $this->method;
    foreach ($this->required_fields as $field):
      if (is_array($this->$field)):
        foreach ($this->$field as $k => $v):
          $url = $url . '&' . $field . '[]=' . urlencode($v);
        endforeach;
      else:
        $url = $url . '&' . $field . '=' . urlencode($this->$field);
      endif;
    endforeach;
    foreach ($this->optional_fields as $field):
      if (!empty($this->$field)):
        if (is_array($this->$field)):
          foreach ($this->$field as $k => $v):
            $url = $url . '&' . $field . '[]=' . urlencode($v);
          endforeach;
        else:
          $url = $url . '&' . $field . '=' . urlencode($this->$field);
        endif;
      endif;
    endforeach;
    $url = $url . '&format=' . $this->format;

    $this->request = $url;

    // Return the request URL
    return $this->request;
  }

  /**
   * Parse Response
   *
   * Adds an error condition if the response is empty.
   *
   * @param string $response - Response in string format.
   * @return string - Response in string format.
   */
  private function parse_response($response) {
    $this->response = $response;
    if (empty($this->response)):
      throw new RavenToolsAPIException("The request for '{$this->request}' returned an empty response.", 500);
    endif;

    return $this->response;
  }

}

/**
 * Raven Tools API Transport
 */
class RavenToolsAPITransport {
  /**
   * cURL
   *
   * @param string $url - URL to query
   * @param array $get - Associative array of $_GET query string parameters
   * @param array $options - Specific cURL options
   * @return string - Response from remote host
   * @link http://www.php.net/manual/en/function.curl-exec.php#98628
   */
  public function curl($url, array $get = array(), array $options = array())
  {
      $defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 30
      );

      $ch = curl_init();
      curl_setopt_array($ch, ($options + $defaults));
      $result = curl_exec($ch);
      $result_info = curl_getinfo($ch);
      if ($result_info['http_code'] != 200 && $result_info['http_code'] != 201):
        $msg = curl_error($ch) ? curl_error($ch) : 'Response: ' . $result;
        curl_close($ch);
        throw new RavenToolsAPIException($msg, $result_info['http_code']);
      endif;
      curl_close($ch);

      return $result;
  }
}

/**
 * Raven Tools API Exception Handler
 */
class RavenToolsAPIException extends Exception {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}
