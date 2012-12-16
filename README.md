[![Build Status](https://api.travis-ci.org/stephenyeargin/raventools-api-php.png)](https://travis-ci.org/stephenyeargin/raventools-api-php)

# raven-api-php

A PHP client for the [Raven Tools API](https://api.raventools.com/docs/).

## License and Disclaimer

This source code for the client is licensed under the terms found in the `LICENSE` file therein. This library is not produced by Raven Tools or its parent company.

## Features

* Interacts with the Raven Tools API for managing websites, keywords and links

## Using the Library

Full class documentation is available in the `/docs` folder of the repository after running `phpdoc` (requires separate installation), which can be viewed in any web browser by opening the `index.html` page. In its most basic example:

    // Create an object from the class using your API key
    require 'path/to/raventools-api-php/src/class.raven-api-php.php';
    $Raven = new RavenToolsAPI( 'YOUR_API_KEY' );
    
    // Make a request using one of the provided methods
    $domains = $Raven->GetDomains();
    
    // $domains now contains a list of domains (websites) in the Profile
    // associated with the provided API key

## Working with API Responses

By default, the library will return PHP objects/arrays as API responses. Any of the convenience methods (e.g. `GetDomains`, `GetKeywords`, `AddLinks`) will always return in this manner such as in the example above. Additionally, direct output can be obtained by using one of the three methods below.

    $objectOrArray = $Raven->get($method, $options);
    $jsonString    = $Raven->getJSON($method, $options);
    $xmlString     = $Raven->getXML($method, $options);

* `$method` (string) - Name of the API method; see the [Raven Tools API Documentation](https://api.raventools.com/docs/)
* `$options` (array) - URL parameters to pass to the given method.

## Exceptions

Outbound requests are checked for required fields based prior to sending the API request. If a required parameter is missing or blank, an exception will be thrown. Additionally, malformed or empty responses from the API will also throw an exception. It is recommended that you wrap API keys in `try`/`catch` constructs.

    try {
      $domains = $Raven->GetDomains();
    } catch (Exception $e) {
      // Do something in the event of an exception
      print 'Error: ' . $e->getMessage();
      exit;
    }

## Where do I get my API key?

An API Key is required for interating with the Raven Tools API. Users can generate an API Key for each 'Profile' in their account. [These instructions](https://raven.zendesk.com/entries/243600-raven-api) detail how to get an API key within the platform.