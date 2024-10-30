<?php

namespace IndicativeWp;

use Dragon\User as DragonUser;
use Dragon\DragonException;
use Dragon\Log;
use Dragon\View;
use Illuminate\Support\Str;
use IndicativeWp\Models\User;

class Indicative
{
	public static function displaySnippet()
	{
		$apiKey = get_option('indicative_api_key', null);
		if (empty($apiKey)) {
			return;
		}
		
		$encryptedOpeningCurlyBrace = 'eyJ';
		if (Str::startsWith($apiKey, $encryptedOpeningCurlyBrace)) {
			$apiKey = static::decryptKey($apiKey);
		}
		
		if ($apiKey === false) {
			return;
		}
		
		$pageData = [
			'key'					=> $apiKey,
			'track-sessions'		=> get_option('indicative_record_sessions', 'yes'),
			'session-timeout-mins'	=>  (int)get_option('indicative_session_recording_timeout', 30),
		];
		
		if (DragonUser::isLoggedIn()) {
			
			static::maybeAddEmailAddress($pageData);
			$pageData['alias-id'] = DragonUser::getUserId();
			
		}
		
		View::displayPage('Snippet', $pageData);
	}
	
	private static function maybeAddEmailAddress(&$pageData)
	{
		if (get_option('indicative_track_email_addresses', 'no') === 'yes') {
			$pageData['email'] = User::where('ID', DragonUser::getUserId())->first()->user_email;
		}
	}
	
	private static function decryptKey($apiKey)
	{
		try {
			
			$apiKey = dragonDecrypt($apiKey);
			return $apiKey;
			
		} catch (DragonException $e) {
			
			Log::info('An error occurred. Please make sure that your API key is configured in the settings area.');
			return false;
			
		}
	}
}
