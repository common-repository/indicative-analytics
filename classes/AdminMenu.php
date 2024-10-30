<?php

namespace IndicativeWp;

use Dragon\Abstracts\AdminMenu as DragonMenu;

class AdminMenu extends DragonMenu
{
	protected static $rootMenu = [
		'slug'			=> 'indicative-settings',
		'menu-text'		=> 'Indicative',
		'custom-icon'	=> 'indicative-menu-icon.png',
		'page-title'	=> 'Indicative',
		'capabilities'	=> 'manage_options',
	];
	
	protected static $submenus = [
		'indicative-settings' => [
			'slug'			=> 'indicative-settings',
			'menu-text'		=> 'Settings',
			'page-title'	=> 'Indicative Settings',
			'capabilities'	=> 'manage_options',
			'callback'		=> 'IndicativeWp\\Pages\\AdminOptions',
		],
		'indicative-log' => [
			'slug'			=> 'indicative-log',
			'menu-text'		=> 'Log',
			'page-title'	=> 'Indicative Log',
			'capabilities'	=> 'manage_options',
			'callback'		=> 'IndicativeWp\\Pages\\AdminLog',
		],
	];
}

