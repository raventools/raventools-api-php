[![Build Status](https://api.travis-ci.org/stephenyeargin/raventools-api-php.png)](https://api.travis-ci.org/stephenyeargin/raventools-api-php)

## Basic Usage

This PHP class provides an interface to the Raven Tools API. To get started, first instantiate the class. Here, we simply call it $Raven.

    require 'path/to/raventools-api-php/src/class.raven-api-php.php';
    $Raven = new RavenToolsAPI( 'YOUR_API_KEY' );

With the new `$Raven` instance, we now have three different options for pulling in the data we need.

### get()

    $responseObject = $Raven->get($method, $options);

The `get()` method calls the `getJSON()` method, and then parses the JSON response into a PHP object. For ease of use, this is probably the one you will most likely need unless you are setting up another layer, such as an AJAX loader. Remember to handle exceptions that may be thrown in this method.

### getJSON()

    $responseString = $Raven->getJSON($method, $options);

The 'getJSON()' method just retrieves the JSON formatted response as a string and does not decode it into a PHP object. This is a friendly way to work with the results in JavaScript.

### getXML()

    $responseString = $Raven->getXML($method, $options);

The `getXML()` method just retrieves the XML formatted response as a string. If you are using XSLT or are passing the XML directly on to another system, this is a useful format.

### $method

This variable defines which data set you want to be returned. See the [API Documentation](https://api.raventools.com/docs/) for a list of methods.

### $options

This variable (as an associative array) defines the parameters for the requested data set. Some methods only require the method name (examples: `domains`, `profile_info`, `domains`) while others require additional parameters (examples: `rank`, `rank_all`, `rank_max_week`). To use this, you define the option in a parameter => value format. To see the list of required options, refer to the [API Documentation](https://api.raventools.com/docs/).

    $rank = $Raven->get('rank', array('keyword'=>'foo bar','domain'=>'www.example.com','start_date'=>'2013-01-01','end_date'=>'2013-01-31') );
    // $rank now contains a PHP object that can be iterated through to display results`

## Exceptions

It is recommended that you wrap your ->get() or helper methods listed below with exception handling for the 'RavenToolsAPIException' class to avoid uncaught exceptions in the event of invalid user input or provider latency.

## Helper Methods

There are also a series of 'helper' methods that assemble some of the request rather than having to create '$options' arrays for a request. Date formatting is also handled in these helper methods.

* `$Raven->getProfileInfo()`
* `$Raven->getDomains()`
* `$Raven->addDomain( $domain, $engines )`
* `$Raven->removeDomain( $domain )`
* `$Raven->getEngines()`
* `$Raven->getDomainInfo( $domain )`
* `$Raven->getRank( $domain, $keyword, $start_date, $end_date, $engine='all' )`
* `$Raven->getRankAll( $domain, $start_date, $end_date )`
* `$Raven->getRankMaxWeek( $domain, $keyword )`
* `$Raven->getCompetitiors( $domain )`
* `$Raven->getKeywords( $domain )`
* `$Raven->addKeyword( $domain, $keyword )`
* `$Raven->removeKeyword ( $domain, $keyword )`
* `$Raven->getLinks ( $domain )`

## Where do I get my API key?

To use the Raven Tools API, you must first sign up for an account (Free 30 Day Trial). After doing so, you can find the API section under Settings (the gear icon) > Profile Manager and select the desired profile. You will need to generate a new API key if you have not already done so.