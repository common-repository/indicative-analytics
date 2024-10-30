<?php

namespace IndicativeWp\Pages;

use Dragon\View;
use Dragon\Abstracts\OptionsAbstract;

class AdminOptions extends OptionsAbstract
{
	protected $requiredFields = [
		'indicative_api_key',
		'indicative_code_snippet_placement',
		'indicative_track_email_addresses',
		'indicative_track_link_clicks',
		'indicative_record_sessions',
		'indicative_session_recording_timeout',
	];
	
	protected $saveButton = 'indicative_save_settings';
	
	public function render()
	{
		$pageData = array();
		$this->saveSettings($pageData);
		
		View::displayPage('AdminOptions', $pageData);
	}
}
