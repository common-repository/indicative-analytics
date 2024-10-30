<?php

namespace Dragon;

class Utility
{
	public static function arrayDot(array $array, $key, $default = null)
	{
		$keyParts = explode('.', $key);
		$result = $array;
		
		for ($i = 0; $i < count($keyParts); $i++) {
			
			$keyName = $keyParts[$i];
			
			if ($i === count($keyParts)-1) {
				return is_array($result) && array_key_exists($keyName, $result) ? $result[$keyName] : $default;
			}
			
			if (!is_array($result) || !array_key_exists($keyName, $result)) {
				return $default;
			}
			
			$result = $result[$keyName];
			
		}
		
		return $default;
	}
	
	public static function isEmail($email)
	{
		return is_email($email) || filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}
