<?php

namespace Google\Analytics\MeasurementProtocol;

/*
 * @Author: Jorgen Horstink <jorgen@moneymonk.nl>
 */

// I don't like auto loading magic, just a manually created list based on the dependency graph
class Requirer {
	public static function once() {
		$files = array(
			'Sendable', 
			'Visitor', 
			'ContentInformation', 
			'cookie/CookieParser',
			'cookie/CookieParseException',
			'hit/Hit',
			'hit/Event',
			'transport/Transport',
			'transport/SocketTransport',
			'transport/StubTransport'
		);

		foreach ($files as $file) {
			require_once __dir__ . '/' . $file . '.php';
		}
	}
}

Requirer::once();

class Tracker {
	private $trackingId;
	private $visitor;
	private $transport;
	
	private $baseParameters;
	
	protected function __construct($trackingId, Visitor $visitor, Transport $transport) {
		$this->trackingId = $trackingId;
		$this->visitor = $visitor;
		$this->transport = $transport;
		
		$protocolVersion = 1;
		$clientId = $this->visitor->getClientId();
		
		$this->baseParameters = array(
			'v' => $protocolVersion,
			'tid' => $trackingId
		);
		
		$this->append($visitor);
	}

	public static function create($trackingId, Visitor $visitor, Transport $transport) {
		return new self($trackingId, $visitor, $transport);
	}
	
	public function append(Sendable $sendable) {
		$this->baseParameters = $this->merge($this->baseParameters, $sendable->getParameters());
	}
	
	public function send(Hit $hit) {
		$parameters = $this->baseParameters;
		$parameters = $this->merge($parameters, $hit->getParameters());
		$parameters = $this->filterNullValues($parameters);
		
		$headers = array(
			'Connection' => 'Close'
		);

		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$headers['User-Agent'] = $_SERVER['HTTP_USER_AGENT'];
		}
		
		if (isset($_SERVER['HTTP_REFERER'])) {
			$headers['Referer'] = $_SERVER['HTTP_REFERER'];
		}
		
		return $this->transport->send('POST', 'http://www.google-analytics.com/collect', $parameters, $headers);
	}

	private function merge($a, $b) {
		return array_merge($a, $b);
	}
	
	// Before sending the data to GA, filter all key/values where the value is NULL
	// These are values not provided, and we don't want to send them to GA.
	private function filterNullValues($array) {
		$result = array();
		foreach ($array as $key => $value) {
			if ($value !== null) {
				$result[$key] = $value;
			}
		}
		return $result;
	}
}

?>