<?php
/**
 * Raven Tools API for PHP
 */

namespace RavenTools;

/**
 * Raven Tools API PHP Class
 *
 * The Raven Tools API allows developers to access and modify data within platform profiles and websites. For more information on the API, visit [their site](https://api.raventools.com/docs/).
 *
 * <code>// Namespaced class
 * use \RavenTools;
 * // Path to the RavenToolsAPI class
 * require_once 'path/to/raventools-api-php/src/class.raven-api-php.php';
 * // Instance to use for calls
 * $Raven = new \RavenTools\RavenToolsAPI('YOUR_API_KEY');</code>
 *
 * @link https://github.com/stephenyeargin/raventools-api-php
 * @package RavenToolsAPI
 * @version 1.2
 */
class RavenToolsAPI {

  /**
   * API Version
   *
   * Version of the Raven API to use
   */
  const api_version = '1.0';

  /**
   * Endpoint
   *
   * Endpoint for connecting to the Raven API
   */
  const endpoint = 'https://api.raventools.com/api';

  /**
   * API Key
   */
  private $api_key;

  /**
   * Transport
   */
  private $transport;

  /**
   * Method
   */
  public $method;

  /**
   * Domain
   */
  public $domain;

  /**
   * Start Date
   */
  public $start_date;

  /**
   * End Date
   */
  public $end_date;

  /**
   * Search Engine
   */
  public $engine;

  /**
   * Keyword
   */
  public $keyword;

  /**
   * Format
   */
  public $format;

  /**
   * API Request
   */
  public $request;

  /**
   * API Response
   */
  public $response;

  /**
   * Required Fields
   */
  public $required_fields;

  /**
   * Optional Fields
   */
  public $optional_fields;

  /**
   * Constructor
   *
   * Instance of the Raven Tools API.
   * <code>$Raven = new RavenToolsAPI('YOUR_API_KEY');</code>
   *
   * @param string $api_key API Key provided within Raven
   * @param object $transport Optional transport handler
   */
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

  /**
   * Get Profile Information
   *
   * This request will return the name and billable keyword usage for the current profile.
   * <code>$profile_info = $Raven->getProfileInfo();</code>
   *
   * @return object Profile information
   */
  public function getProfileInfo() {
    return $this->get('profile_info');
  }

  /**
   * Get Domains
   *
   * This request will return the available domains for the profile associated with your API key.
   * <code>$domains = $Raven->geDomains();</code>
   *
   * @return object API response
   */
  public function getDomains() {
    return $this->get('domains');
  }

  /**
   * Add Domain
   *
   * This request will add the domain provided.
   * <code>$result = $Raven->addDomain($domain, $engines);</code>
   *
   * @param string $domain The domain name you want to add; "www." prefixes are ignored for purposes of matching ranks, but will be stored as part of the domain name for future requests
   * @param array $engines Ordered array of search engine ids that you want to track for this domain
   * @return object API response
   */
  public function addDomain( $domain = '', $engines = array() ) {
    if (!isset($domain) || empty($domain)) {
      throw new RavenToolsAPIException('The domain or engine was not set as part of this request. Required by addDomain().', 500);
    }

    if (is_array($engines) && !empty($engines)) {
      $engines = implode(',', $engines);
    }

    return $this->get('add_domain', array('domain'=>$domain,'engine_id'=>$engines) );
  }

  /**
   * Remove Domain
   *
   * This request will permanently remove the specified domain.
   * <code>$result = $Raven->removeDomain($domain);</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @return object API response
   */
  public function removeDomain ( $domain = '' ) {
    if (!isset($domain) || empty($domain)) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by removeDomain().', 500);
    }

    return $this->get('remove_domain', array('domain'=>$domain) );
  }

  /**
   * Get Engines
   *
   * This request will return the available search engines for tracking keywords, to be used when adding or modifying domains.
   * <code>$engines = $Raven->getEngines();</code>
   *
   * @return object API response
   * @deprecated Raven to remove on or around 2013-01-02
   */
  public function getEngines() {
    return $this->get('engines');
  }

  /**
   * Get Domain Info
   *
   * This request will return information for the domain provided.
   * <code>$domain = $Raven->getDomainInfo($domain);</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @return object API response
   */
  public function getDomainInfo( $domain = '' ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by getDomainInfo().', 500);
    }

    return $this->get('domain_info', array('domain'=>$domain) );
  }

  /**
   * Get Rank
   *
   * This request will return a list of matches for a particular domain, keyword, search engine, and date range. You can only access results for domains and keywords that have been added to your account, including competitor domains.
   * <code>$rank = $Raven->getRank( $domain, $keyword, $start_date, $end_date, $engine );</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @param string $keyword Keyword in the Keyword Manager of the given domain
   * @param string $start_date Start date for rank
   * @param string $end_date End date for rank
   * @param string|array $engine String of 'all' or ordered array of engine_id
   * @return object API response
   * @deprecated Raven to remove on or around 2013-01-02
   */
  public function getRank( $domain = '', $keyword = '', $start_date = '', $end_date = '', $engine = 'all' ) {
    if ( empty($domain) || empty($keyword) || empty($start_date) || empty($end_date) || empty($engine) ) {
      throw new RavenToolsAPIException('The domain, keyword, start date, end date or engine was not set as part of this request. Required by getRank().', 500);
    }

    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));

    return $this->get('rank', array('keyword'=>$keyword,'domain'=>$domain,'start_date'=>$start_date,'end_date'=>$end_date,'engine'=>$engine) );
  }

  /**
   * Get Ranking for All Keywords
   *
   * This request will return a list of matches for a particular domain and date. You can only access results for domains and keywords that have been added to your account, including competitor domains.
   * <code>$rank_all = $Raven->getRankAll( $domain, $start_date, $end_date );</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @param string $start_date Start date for rank
   * @param string $end_date End date for rank
   * @return object API response
   * @deprecated Raven to remove on or around 2013-01-02
   */
  public function getRankAll( $domain = '', $start_date = '', $end_date = '' ) {
    if ( empty($domain) || empty($start_date) || empty($end_date) ) {
      throw new RavenToolsAPIException('The domain, start date or end date was not set as part of this request. Required by getRankAll().', 500);
    }

    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));

    return $this->get('rank_all', array('domain'=>$domain,'start_date'=>$start_date,'end_date'=>$end_date) );
  }

  /**
   * Get Ranking Max for a Week
   *
   * This request returns the ISO Week number (YYYYWW) and date (YYYY-MM-DD) for the latest week with complete results for all keywords in a domain or a domain/keyword pair. It returns null for date/week and status = 'no data' for domains or domain/keywords that have no available data.
   * <code>$rank_max_week = $Raven->getRankMaxWeek( $domain, $keyword );</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @param string $keyword Keyword in the Keyword Manager of the given domain
   * @return object API response
   * @deprecated Raven to remove on or around 2013-01-02
   */
  public function getRankMaxWeek( $domain = '', $keyword = '' ) {
    if ( empty($domain) || empty($keyword) ) {
      throw new RavenToolsAPIException('The domain or keyword was not set as part of this request. Required by getRankMaxWeek().', 500);
    }

    return $this->get('rank_max_week', array('domain'=>$domain,'keyword'=>$keyword) );
  }

  /**
   * Get Competitors
   *
   * This request will return the available competitors for the domain provided.
   * <code>$competitors = $Raven->getCompetitors( $domain );</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @return object API response
   */
  public function getCompetitors( $domain = '' ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by getCompetitors().', 500);
    }

    return $this->get('competitors', array('domain'=>$domain) );
  }

  /**
   * Get Keywords
   *
   * This request will return the available keywords for the domain provided.
   * <code>$keywords = $Raven->getKeywords($domain);</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @return object API response
   */
  public function getKeywords( $domain = '' ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by getKeywords().', 500);
    }

    return $this->get('keywords', array('domain'=>$domain) );
  }

  /**
   * Get Keywords with Tags
   *
   * This request will return the available keywords and their tags for the domain provided.
   * <code>$keywords = $Raven->getKeywordsTags($domain);</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @return object API response
   */
  public function getKeywordsTags( $domain = '' ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by getKeywordsTags().', 500);
    }

    return $this->get('keywords_tags', array('domain'=>$domain) );
  }

  /**
   * Add Keyword
   *
   * This request will add keyword to the domain provided.
   * <code>$result = $Raven->addKeyword($domain, $keyword);</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @param string $keyword Keyword in the Keyword Manager of the given domain
   * @return object API response
   */
  public function addKeyword( $domain = '', $keyword = '' ) {
    if ( empty($domain) || empty($keyword) ) {
      throw new RavenToolsAPIException('The domain or keyword was not set as part of this request. Required by addKeyword().', 500);
    }

    return $this->get('add_keyword', array('domain'=>$domain,'keyword'=>$keyword) );
  }

  /**
   * Remove Keyword
   *
   * This request will remove keyword from the domain provided.
   * <code>$result = $Raven->removeKeyword($domain, $keyword);</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @param string $keyword Keyword in the Keyword Manager of the given domain
   * @return object API response
   */
  public function removeKeyword( $domain = '', $keyword = '' ) {
    if ( empty($domain) || empty($keyword) ) {
      throw new RavenToolsAPIException('The domain or keyword was not set as part of this request. Required by removeKeyword().', 500);
    }

    return $this->get('remove_keyword', array('domain'=>$domain,'keyword'=>$keyword) );
  }

  /**
   * Get Links
   *
   * This request will return the all links for the domain provided.
   * <code>$links = $Raven->getLinks($domain, $tag);</code>
   *
   * @param string $domain Domain of website within the profile, must match exactly
   * @param string $tag Filter results to a particular tag, must match exactly (optional)
   * @return object API response
   */
  public function getLinks( $domain = '', $tag = '' ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by getLinks().', 500);
    }

    return $this->get('get_links', array('domain'=>$domain, 'tag'=>$tag) );
  }

  /**
   * Get Website Types
   *
   * This request will retrieve all of the default website types along with any custom website types your account setup in the sytem.
   * <code>$website_types = $Raven->getWebsiteTypes();</code>
   *
   * @return object API response
   */
  public function getWebsiteTypes() {
    return $this->get('get_website_types');
  }

  /**
   * Get Link Types
   *
   * This request will retrieve all of the default link types along with your custom link types your account has in the sytem.
   * <code>$website_types = $Raven->getWebsiteTypes();</code>
   *
   * @return object API response
   */
  public function getLinkTypes() {
    return $this->get('get_link_types');
  }

  /**
   * Add Links
   *
   * This request allows you to pass in an array with link data for the links you would like to create and returns a list of new Link IDs.
   * <code>$result = $Raven->addLinks($domain, $links);</code>
   *
   * @param string $domain The domain name you want the links to be added under; This value is optional, it can be passed in on the individual link records as well, but must be passed in either here or on each link record
   * @param array|string $links Array of link objects or JSON encoded string
   * @return object API response
   */
  public function addLinks( $domain = '', $links = array() ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by addLinks().', 500);
    }
    $link = $this->check_json_object($links, 'addLinks');

    return $this->get('add_links', array('domain'=>$domain, 'link'=>$link) );
  }

  /**
   * Update Links
   *
   * This request allows you to pass in an array with link data for the links you would like to update and returns a list of the link IDs and if they were properly updated.
   * <code>$result = $Raven->updateLinks($domain, $links);</code>
   *
   * @param string $domain The domain name you want the links to be added under; This value is optional, it can be passed in on the individual link records as well, but must be passed in either here or on each link record
   * @param array|string $links Array of link objects or JSON encoded string
   * @return object API response
   */
  public function updateLinks( $domain = '', $links = array() ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by updateLinks().', 500);
    }

    $link = $this->check_json_object($links, 'updateLinks');

    return $this->get('update_links', array('domain'=>$domain, 'link'=>$link) );
  }

  /**
   * Delete Links
   *
   * This request allows you to pass in an array with link data for the links you would like to update and returns a list of the link ID's and if they were properly updated.
   * <code>$result = $Raven->deleteLinks($domain, $links);</code>
   *
   * @param string $domain The domain name you want the links to be added under; This value is optional, it can be passed in on the individual link records as well, but must be passed in either here or on each link record
   * @param array|string $links Array of link objects or JSON encoded string
   * @return object API response
   */
  public function deleteLinks( $domain = '', $links = array() ) {
    if ( empty($domain) ) {
      throw new RavenToolsAPIException('The domain was not set as part of this request. Required by deleteLinks().', 500);
    }

    $link = $this->check_json_object($links, 'deleteLinks');

    return $this->get('delete_links', array('domain'=>$domain, 'link'=>$link) );
  }

  /* Core query methods */

  /**
   * Get JSON
   *
   * Retrieves a JSON string in response to a particular API method and options.
   * <code>$result = $Raven->getJSON($method, $options);</code>
   *
   * @param string $method Defines specific method to query (part of query string, sets required fields)
   * @param array $options Defines options to be passed to query string
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
   * Retrieves an XML string in response to a particular API method and options.
   * <code>$result = $Raven->getXML($method, $options);</code>
   *
   * @param string $method Defines specific method to query (part of query string, sets required fields)
   * @param array $options Defines options to be passed to query string
   * @return string XML response
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
   * Retrieves a PHP object in response to a particular API method and options.
   * <code>$result = $Raven->get($method, $options);</code>
   *
   * @param string $method Defines specific method to query (part of query string, sets required fields)
   * @param array $options Defines options to be passed to query string
   * @return object API response
   */
  public function get($method, $options = array()) {
    $this->setMethod($method);
    if ($this->format == 'json') {
      $response = $this->getJSON($method, $options);

      return json_decode($response);
    } else {
      $response = $this->getXML($method, $options);

      return new \SimpleXMLElement($response);
    }
  }

  /* Static methods */

  /**
   * Validate API Key
   *
   * Validates a given API Key before issuing commands.
   *
   * <code>
   * if (RavenToolsAPI::validateAPIKey('somekey') == true) {
   *   echo 'valid key';
   * } else {
   *   echo 'invalid key';
   * }</code>
   *
   * @param string $key Key to be validated
   * @return boolean True upon success, false if no response
   */
  public static function validateAPIKey($key) {
    $testing = new self($key);
    try {
      $result = $testing->get('domains');
    } catch (Exception $e) {
      return false;
    }
    if (is_array($result)) {
      return true;
    } else {
      return false;
    }
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

    switch ($method) {

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
        $this->required_fields = array('domain', 'keyword');
      break;

      case 'remove_keyword':
        $this->required_fields = array('domain', 'keyword');
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

      case 'add_links':
        $this->required_fields = array('link');
        $this->optional_fields = array('domain');
      break;

      case 'update_links':
        $this->required_fields = array('link');
        $this->optional_fields = array('domain');
      break;

      case 'delete_links':
        $this->required_fields = array('link');
        $this->optional_fields = array('domain');
      break;

      default:
        throw new RavenToolsAPIException("'{$method}' was not recognized as a valid method.", 500);
        break;

    }
  }

  /**
   * Get Response
   *
   * Primary processing method. Makes the call to build the URL, error checks and processes the cURL request.
   *
   * @param string $options Options passed from get(), getJSON() or getXML()
   * @return object|string|boolean Response from query or false
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
   */
  private function check_required() {
    foreach ($this->required_fields as $field) {
      if (!isset($this->$field) || empty($this->$field)) {
        throw new RavenToolsAPIException("The '{$field}' was not set as part of this request. Required by '{$this->method}' method.", 500);
      }
    }
  }

  /**
   * Build Request URL
   *
   * Constructs the URL to send based on the options passed in. Only uses those in the required_fields and optional_fields properties.
   *
   * @param array $options An array of options passed from get(), getJSON() or getXML()
   * @return string URL to be requested
   */
  private function build_request_url( $options = array() ) {

    // Take the options array and set the properties
    foreach ($options as $key => $value) {
      $this->$key = $value;
    }

    // Verify that every required attribute was specified, throw exception if not
    $this->check_required();

    // Begin building the URL for the request
    $url = self::endpoint . '?key=' . $this->api_key . '&method=' . $this->method;
    foreach ($this->required_fields as $field) {
      if (is_array($this->$field)):
        foreach ($this->$field as $k => $v):
          $url = $url . '&' . $field . '[]=' . urlencode($v);
        endforeach;
      else:
        $url = $url . '&' . $field . '=' . urlencode($this->$field);
      endif;
    }
    foreach ($this->optional_fields as $field) {
      if (!empty($this->$field)) {
        if (is_array($this->$field)) {
          foreach ($this->$field as $k => $v) {
            $url = $url . '&' . $field . '[]=' . urlencode($v);
          }
        } else {
          $url = $url . '&' . $field . '=' . urlencode($this->$field);
        }
      }
    }
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
   * @param string $response Response in string format.
   * @return string Response in string format.
   */
  private function parse_response($response) {
    $this->response = $response;
    if (empty($this->response)) {
      throw new RavenToolsAPIException("The request for '{$this->request}' returned an empty response.", 500);
    }

    return $this->response;
  }

  /**
   * Check JSON Object
   *
   * Encodes array into a JSON string, or passes JSON directly through
   *
   * @param string $object Object or JSON string to be checked
   * @param string $method Associated method name
   */
  private function check_json_object( $object = array(), $method = '' ) {
    if ( !is_array($object) ) {
      $_tmp = json_decode($object);
      if (!$_tmp || !is_array($_tmp) || count($_tmp) < 1) {
        throw new RavenToolsAPIException("The items passed were not an array, not a JSON object or was empty. Required by {$method}().", 500);
      } else {
        // Already encoded JSON, pass directly along
        return $object;
      }
    } else {
      return json_encode($object);
    }
  }

}

/**
 * Raven Tools API Transport
 *
 * @package RavenToolsAPI
 */
class RavenToolsAPITransport {
  /**
   * cURL Request
   *
   * Interacts with PHP's cURL methods to retrieve API response.
   *
   * @param string $url URL to query
   * @param array $get Associative array of $_GET query string parameters
   * @param array $options Specific cURL options
   * @return string Response from remote host
   * @link http://www.php.net/manual/en/function.curl-exec.php#98628
   */
  public function curl($url, array $get = array(), array $options = array()) {
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
      if ($result_info['http_code'] != 200 && $result_info['http_code'] != 201) {
        $msg = curl_error($ch) ? curl_error($ch) : 'Response: ' . $result;
        curl_close($ch);
        throw new RavenToolsAPIException($msg, $result_info['http_code']);
      }
      curl_close($ch);

      return $result;
  }
}

/**
 * Raven Tools API Exception Handler
 *
 * @package RavenToolsAPI
 */
class RavenToolsAPIException extends \Exception {

  /**
   * Constructor
   *
   * @param string $message Error message
   * @param int $code Error code
   * @param Exception $previous
   */
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

}
