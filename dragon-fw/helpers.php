<?php

use Dragon\Config;
use Dragon\DragonDecryptionException;
use Dragon\Encrypter;
use Dragon\Log;
use Dragon\Utility;
use Dragon\Lang;

if (!function_exists('dragonEncrypt')) {
	
	function dragonEncrypt($value, $serialize = true) {
		
		$key = Config::$encryptionKey;
		$encryptor = new Encrypter();
		return $encryptor->encrypt($value, $serialize);
		
	}
	
}

if (!function_exists('dragonDecrypt')) {
	
	function dragonDecrypt($payload, $unserialize = true) {
		
		try {
			
			$encryptor = new Encrypter();
			return $encryptor->decrypt($payload, $unserialize);
			
		} catch (DragonDecryptionException $e) {
			
			Log::info('Invalid decryption token. Fill out the ' .
				'missing fields on the settings page, and save ' .
				'the page to fix this error.');
			return null;
			
		}
		
	}
	
}

if (!function_exists('kababToCamel')) {
	
	function kababToCamel($string, $sedateTheCamel = false)
	{
		$shishkaWords = str_replace('-', ' ', $string);
		$titleCase = ucwords($shishkaWords);
		
		// AloofCamel
		$aloofCamel = str_replace(' ', '', $titleCase);
		
		// sedatedCamel
		if ($sedateTheCamel) {
			$aloofCamel[0] = strtolower($aloofCamel[0]);
		}
		
		return $aloofCamel;
	}
	
}

if (!function_exists('arrayDot')) {
	
	function arrayDot(array $array, $key, $default = null)
	{
		return Utility::arrayDot($array, $key, $default);
	}
	
}

if (!function_exists('lang')) {
	
	function lang($fileDot, $key, $default = null)
	{
		return Lang::get($fileDot, $key, $default);
	}
	
}

if (!function_exists('old')) {
	
	function old($postKey, $default = null)
	{
		return array_key_exists($postKey, $_POST) ? sanitize_text_field($_POST[$postKey]) : $default;
	}
	
}

if (!function_exists('camelTitle')) {
	
	function camelTitle($text)
	{
		$snake = snake_case($text);
		$words = implode(' ', explode('_', $snake));
		
		return title_case($words);
	}
	
}

if (!function_exists('dragonAssets')) {
	
	function dragonAssets($relativeLink)
	{
		return Config::$pluginBaseUrl . 'assets/' . $relativeLink;
	}
	
}
