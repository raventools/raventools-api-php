<?php

/**
 * Raven Tools API PHP Class
 *
 * @link https://github.com/stephenyeargin/raventools-api-php
 * @package default
 */
class RavenTools {

  const api_version = '1.0';
  const end_point = 'http://raven-seo-tools.com/api';

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
  public $errors;

  function __construct($api_key = null) {
    $this->api_key = $api_key;
      if (empty($api_key)) { die('You must provide an API key for the desired Raven Tools profile.'); }
    $this->format = 'json';
    $this->required_fields = array();
    $this->optional_fields = array();
    $this->errors = array();
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
   * @param string $engines 
   */
  public function addDomain( $domain, $engines = array() ) {
    if (!isset($dmain) || empty($domain)):
      return false;
    endif;
    
    if (is_array($engines) && !empty($engines)):
      $engines = implode(',', $engines);
    endif;
    
    $this->get('add_domain', array('domain'=>$domain,'engines'=>$engines) );
  }

  /**
   * Remove Domain
   *
   * @param string $domain
   */
  public function removeDomain ( $domain ) {
    if (!isset($dmain) || empty($domain)):
      return false;
    endif;

    $this->get('add_domain', array('domain'=>$domain) );
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
  public function getDomainInfo( $domain ) {
    if (empty($domain)):
      return false;
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
  public function getRank( $keyword, $domain, $start_date, $end_date, $engine='all' ) {
    if ( !isset($keyword, $domain, $start_date, $end_date) || empty($domain) || empty($keyword) ):
      return false;
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
  public function getRankAll( $domain, $start_date, $end_date ) {
    if ( !isset($domain, $start_date, $end_date) || empty($domain) ):
      return false;
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
  public function getRankMaxWeek( $domain, $keyword ) {
    if ( !isset($domain, $keyword) || empty($domain) || empty($keyword) ):
      return false;
    endif;
    
    return $this->get('rank_max_week', array('domain'=>$domain,'keyword'=>$keyword) );   
  }
  
  /**
   * Get Competitors
   *
   * @param string $domain 
   * @return object
   */
  public function getCompetitiors( $domain ) {
    if ( !isset($domain) || empty($domain) ):
      return false;
    endif;
    
    return $this->get('competitors', array('domain'=>$domain) );
  }
  
  /**
   * Get Keywords
   *
   * @param string $domain 
   * @return object
   */
  public function getKeywords( $domain ) {
    if ( !isset($domain) || empty($domain) ):
      return false;
    endif;

    return $this->get('keywords', array('domain'=>$domain) );
  }

  /**
   * Add Keyword
   *
   * @param string $keyword 
   * @param string $domain 
   * @return void
   */
  public function addKeyword( $keyword, $domain ) {
    if ( !isset($domain, $keyword) || empty($domain) || empty($keyword) ):
      return false;
    endif;

    return $this->get('add_keyword', array('domain'=>$domain,'keyword'=>$keyword) );
  }

  /**
   * Remove Keyword
   *
   * @param string $keyword 
   * @param string $domain 
   * @return void
   */
  public function removeKeyword( $keyword, $domain ) {
    if ( !isset($domain, $keyword) || empty($domain) || empty($keyword) ):
      return false;
    endif;

    return $this->get('remove_keyword', array('domain'=>$domain,'keyword'=>$keyword) );  
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
    $response = $this->getJSON($method, $options);
    return json_decode($response);
  }


  /* Static methods */

  /**
   * Validate API Key
   *
   * @param string $key - Key to be validated
   * @return boolean - true upon success, false if no response
   */
  static function validateAPIKey($key) {
    $testing = new self($key);
    $result = $testing->get('domains');
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

      case 'add_keyword':
        $this->required_fields = array('keyword', 'domain');
      break;

      case 'remove_keyword':
        $this->required_fields = array('keyword', 'domain');
      break;

      default:
        $this->addError('raven_invalid_method', "'{$method}' was not recognized as a valid method.");
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
    if ($this->hasErrors() == false):
      $response = $this->curl($url);
      return $this->parse_response($response);
    else:
      return false;
    endif;
  }
  
  /**
   * Check Required Fields
   *
   * Iterates through the 'required_fields' property array to ensure that all necessary properties are set prior to a request being made.
   *
   * @return void
   */
  private function check_required() {
    foreach($this->required_fields as $field) {
      if (!isset($this->$field) || empty($this->$field)):
        $this->addError('raven_missing_required_field', "The '{$field}' was not set as part of this request. Required by '{$this->method}' method.");
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

    // Verify that every required attribute was specified, send to $errors array if not
    $this->check_required();

    // Begin building the URL for the request
    $url = self::end_point . '?key=' . $this->api_key . '&method=' . $this->method;
    foreach ($this->required_fields as $field):
      $url = $url . '&' . $field . '=' . urlencode($this->$field);
    endforeach;
    foreach ($this->optional_fields as $field):
      if (!empty($this->$field)):
        $url = $url . '&' . $field . '=' . urlencode($this->$field);
      endif;
    endforeach;
    $url = $url . '&format=' . $this->format;

    $this->request = $url;

    // Return the request URL
    return $this->request;
  }
  
/**
 * cURL
 *
 * @param string $url - URL to query
 * @param array $get - Associative array of $_GET query string parameters
 * @param array $options - Specific cURL options
 * @return string - Response from remote host
 * @link http://www.php.net/manual/en/function.curl-exec.php#98628
 */
  private function curl($url, array $get = array(), array $options = array())
  {   
      $defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4
      );

      $ch = curl_init();
      curl_setopt_array($ch, ($options + $defaults));
      if( ! $result = curl_exec($ch)):
        //trigger_error(curl_error($ch));
      endif;
      curl_close($ch);

      return $result;
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
      $this->addError('raven_empty_response', "The request for '{$this->request}' returned an empty response.");
    endif;
    return $this->response;
  }

  /**
   * Add Error
   *
   * Adds an error to the $errors array
   *
   * @param string $key - Unique identifier for error message
   * @param string $msg - Text message for error
   * @return void
   */
  private function addError($key, $msg) {
    $this->errors[$key] = $msg;
  }

  /**
   * Has Errors
   *
   * Returns true or false based on whether the $errors array is empty
   *
   * @return boolean
   */
  private function hasErrors() {
    if (empty($this->errors)) {
      return false;
    } else {
      return true;
    }
  }

}