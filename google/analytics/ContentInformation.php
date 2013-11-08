<?php

namespace Google\Analytics\MeasurementProtocol;

class ContentInformation implements Sendable {
	private $location;
	private $hostname;
	private $path;

	private function __construct($location, $hostname, $path) {
		$this->location = $location;
		$this->hostname = $hostname;
		$this->path = $path;
	}
	
	public static function create() {
		$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		
		$path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
		$hostname = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
		$location = $path !== null ? $protocol . $hostname . $path : null;
		
		return new self($location, $hostname, $path);
	}
	
	public function getLocation() {
		return $this->location;
	}
	
	public function getHostname() {
		return $this->hostname;
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getParameters() {
		return array(
			'dl' => $this->location,
			'dh' => $this->hostname,
			'dp' => $this->path
		);
	}
}

?>