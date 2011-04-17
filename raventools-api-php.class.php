<?php

/**
 * RavenTools API PHP Class
 *
 * @link https://github.com/stephenyeargin/raventools-api-php
 * @package default
 */
class RavenTools {
  
  private $api_key;
  private $end_point;
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
    $this->end_point = 'http://raven-seo-tools.com/api';
    $this->format = 'json';
    $this->engine = 'google';
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
   * @return array - decoded JSON response
   */
  public function get($method, $options = array()) {
    $this->setMethod($method);
    $response = $this->getJSON($method, $options);
    return json_decode($response);
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
	
    switch ($method) {

      case 'rank':
        $this->method = $method;
        $this->required_fields = array('domain', 'keyword', 'start_date', 'end_date', 'engine');
      break;

      case 'rank_all':
        $this->method = $method;
        $this->required_fields = array('domain', 'start_date');
      break;

      case 'domains':
        $this->method = $method;
        $this->required_fields = array();
      break;

      case 'rank_max_week':
        $this->method = $method;
        $this->required_fields = array('domain');
        $this->optional_fields = array('keyword');
      break;

      case 'engines':
        $this->method = $method;
        $this->required_fields = array();
      break;

      case 'profile_info':
        $this->method = $method;
        $this->required_fields = array();
      break;

      case 'domain_info':
        $this->method = $method;
        $this->required_fields = array('domain');
      break;

      case 'competitors':
        $this->method = $method;
        $this->required_fields = array('domain');
      break;

      case 'keywords':
        $this->method = $method;
        $this->required_fields = array('domain');
      break;

      default:
        $this->addError('raven_invalid_method', "'{$method}' was not recognized as a valid method.");
        break;
    }
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
    if ($this->hasErrors() == false) {
      $response = $this->curl($url);
      return $this->parse_response($response);
    } else {
      return false;
    }
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
      if (!isset($this->$field) || empty($this->$field)) {
        $this->addError('raven_missing_required_field', "The '{$field}' was not set as part of this request. Required by '{$this->method}' method.");
      }
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
    foreach ($options as $key => $value) {
      $this->$key = $value;
    }
    
    // Verify that every required attribute was specified, send to $Errors if not
    $this->check_required();

    // Begin building the URL for the request
    $url = $this->end_point . '?key=' . $this->api_key . '&method=' . $this->method;
    foreach ($this->required_fields as $field) {
      $url = $url . '&' . $field . '=' . urlencode($this->$field);
    }
    foreach ($this->optional_fields as $field) {
      if (!empty($this->$field)) {
        $url = $url . '&' . $field . '=' . urlencode($this->$field);
      }
    }
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
      if( ! $result = curl_exec($ch))
      {
          trigger_error(curl_error($ch));
      }
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
    if ($this->response == '') {
      $this->response = 'no-response';
      $this->addError('raven_empty_response', "The request for '{$this->request}' returned an empty response.");
    }
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