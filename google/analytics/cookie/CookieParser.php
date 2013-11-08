<?php

namespace Google\Analytics\MeasurementProtocol;

class CookieParser {
	public static function getCID($cookie) {
		$parts = explode('.', $cookie);

		if (sizeof($parts) === 4) {
			return $parts[2] . '.' . $parts[3];
		} else {			
			throw new CookieParseException('Not able to obtain the Client ID from the provided cookie.');
		}
	}
}

?>