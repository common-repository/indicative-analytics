<?php

namespace IndicativeWp;

use Dragon\FileSystem;
use Dragon\AdminPluginHooks as DragonAdminPluginHooks;
use Dragon\View;

class AdminPluginHooks extends DragonAdminPluginHooks
{
	protected static $actions = [
		'admin_menu'	=> [AdminMenu::class, 'constructMenus'],
		'admin_notices'	=> [AdminPluginHooks::class, 'maybeShowNotSetUp'],
	];
	
	public static function init()
	{
		FileSystem::loadCss([
			'indicative-fancy-notification'	=> 'fancy-admin-notification.css',
			'indicative-css'	=> 'indicative-admin.css',
		]);
		
		parent::init();
	}
	
	public static function maybeShowNotSetUp()
	{
		$apiKey = get_option('indicative_api_key', null);
		if (empty($apiKey)) {
			View::displayPage('NotSetUp');
		}
	}
}
