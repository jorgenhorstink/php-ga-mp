<?php 

require_once 'google/analytics/Tracker.php';

use Google\Analytics\MeasurementProtocol\ContentInformation;
use Google\Analytics\MeasurementProtocol\CookieParseException;
use Google\Analytics\MeasurementProtocol\Visitor;
use Google\Analytics\MeasurementProtocol\SocketTransport;
use Google\Analytics\MeasurementProtocol\StubTransport;
use Google\Analytics\MeasurementProtocol\Tracker;
use Google\Analytics\MeasurementProtocol\Event;

try {
	// Throws a CookieParseException if it was not able to extract the Client ID from the cookie
	$visitor = Visitor::createFromCookie($_COOKIE['_ga']);
	
	// Tries to fetch the Location, Hostname and Path from the $_SERVER variable.
	$contentInformation = ContentInformation::create();
	
	// Just a dumb Stub Transport for testing, or you can use the SocketTransport
	$transport = StubTransport::getInstance();
	//$transport = SocketTransport::getInstance();
	
	$tracker = Tracker::create('UA-12345678-9', $visitor, $transport);
	$tracker->append($contentInformation);
	
	// Send an Event to the Google server. The last two parameters, label and value are optional
	echo $tracker->send(new Event('goal', 'became-customer', 'monthly', 1900));
	
} catch (CookieParseException $e) {
	echo $e->getMessage();
}

?>