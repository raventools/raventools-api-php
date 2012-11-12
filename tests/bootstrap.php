<?php
/**
 * PHPUnit Bootstrap
 */

$file_root = realpath(dirname(__FILE__) . '/../');

require_once $file_root . '/src/class.raven-api-php.php';
require_once $file_root . '/tests/RavenToolsAPI/RavenToolsAPITransportMock.php';
