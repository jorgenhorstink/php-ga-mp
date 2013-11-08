<?php

namespace Google\Analytics\MeasurementProtocol;

class StubTransport implements Transport {
	private static $instance;
	
	private function __construct() {}
	
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function send($method, $url, $parameters = null, $headers = null) {
    	$s = '<pre>';
        $s  = 'Method: ' . $method . "\n";
        $s .= 'URL: ' . $url . "\n";
        
        $s .= "Parameters...\n";
        if (is_array($parameters)) {
        	foreach ($parameters as $key => $value) {
        		$s .= $key . ': ' . $value . "\n";
        	}
        }
        
        $s .= "Headers...\n";
        if (is_array($headers)) {
        	foreach ($headers as $key => $value) {
        		$s .= $key . ': ' . $value . "\n";
        	}
        }
        $s .= '</pre>';
    	
        return $s;
    }
}