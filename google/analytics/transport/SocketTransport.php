<?php

namespace Google\Analytics\MeasurementProtocol;

class SocketTransport implements Transport {
	private static $instance;
	
	private $crlf = "\r\n";
	
	private function __construct() {}
	
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function send($method, $url, $parameters = null, $headers = null) {
        $c = parse_url($url);

        if ($c['scheme'] !== 'http') {
            return null;
        }

        if ($parameters !== null && is_array($parameters) && sizeof($parameters) > 0) {
            $p = array();
            foreach ($parameters as $name => $value) {
                $p[] = urlencode($name) . "=" . urlencode($value);
            }
            $parameters = join('&', $p);
        } else {
            $parameters = '';
        }
        
        if ($headers !== null && is_array($headers) && sizeof($headers) > 0) {
        	$h = array();
        	$hm = array();
        	foreach ($headers as $name => $value) {
        		$hm[strtolower($name)] = 1;
        		$h[] = $name . ': ' . $value . $this->crlf;
        	}
        	   
        	if (!isset($hm['host'])) {
        		$h[] = 'Host: ' . $c['host'] . $this->crlf; 
        	}  
        	$headers = join('', $h);
        } else {
        	$headers = '';
        }

        $req  = $method . ' '. $c['path'] . ($method === 'GET' ? '?' . $parameters : '') . ' HTTP/1.1' . $this->crlf;
		$req .= $headers;

        if ($method === 'POST' && strlen($parameters) > 0) {
            $req .= 'Content-Type: application/x-www-form-urlencoded' . $this->crlf;
            $req .= 'Content-Length: '. strlen($parameters) . $this->crlf . $this->crlf;
            $req .= $parameters;
        } else {
            $req .= $this->crlf;
        }

        if (!isset($c['port']) || $c['port'] == 0) {
            $port = 80;
        } else {
            $port = $c['port'];
        }

        $fp = fsockopen($c['host'], $port, $errno, $errstr, 10);

        if ($fp === false) {
            throw new \Exception();
        }

        fwrite($fp, $req);
        $ret = '';
        while (!feof($fp)) {
            $ret .= fgets($fp, 128);
            flush();
        }
        fclose($fp);

        return substr($ret, strpos($ret, "\r\n\r\n") + 4);
    }
}