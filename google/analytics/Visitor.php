<?php

namespace Google\Analytics\MeasurementProtocol;

class Visitor implements Sendable {
	private $cid;

	protected function __construct($cid) {
		$this->cid = $cid;
	}
	
	public static function createFromCookie($cookie) {
		return new self(CookieParser::getCID($cookie));
	}
	
	public static function createWithUUID() {
		return new self(sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		));
	}

	public static function create($cid) {
		return new self($cid);
	}

	public function getClientId() {
		return $this->cid;
	}
	
	public function getParameters() {
		return array('cid' => $this->cid);
	}
}

?>