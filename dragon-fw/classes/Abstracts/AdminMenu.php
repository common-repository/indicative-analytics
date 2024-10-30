<?php

namespace Dragon\Abstracts;

use Dragon\Config;
use Dragon\FileSystem;

abstract class AdminMenu
{
	protected static $rootMenu = [];
	
	protected static $submenus = [
		/*'dragon-sub' => [
		 'slug'			=> 'dragon-sub',
		 'menu-text'		=> 'Dragon Sub Sandwich',
		 'page-title'	=> 'Dragon Sub Sandwich Page',
		 'capabilities'	=> 'manage_options',
		 'callback'		=> '',
		 ],*/
	];
	
	protected static $hiddenMenus = [
		/* same as $submenus */
	];
	
	protected static $rootMenuDefault = [
		'slug'			=> 'dragon',
		'menu-text'		=> 'Dragon Root',
		'icon'			=> 'dashicons-networking',
		'page-title'	=> 'Dragon Root Page',
		'capabilities'	=> 'manage_options',
		'callback'		=> '',
	];
	
	protected static $submenuDefault = [
		'slug'			=> 'dragon-sub',
		'menu-text'		=> 'Dragon Sub Sandwich',
		'page-title'	=> 'Dragon Sub Sandwich Page',
		'capabilities'	=> 'manage_options',
		'callback'		=> '',
	];
	
	public static function constructMenus()
	{
		static::setCallbacks();
		static::addRootSettingsMenu();
		static::addSubmenus();
		static::addHiddenPages();
	}
	
	private static function setCallbacks()
	{
		static::setCallbacksForRootMenuItem();
		static::setCallbacksForSubmenuItems();
		static::setCallbacksForSubmenuItems(true);
	}
	
	private static function setCallbacksForRootMenuItem()
	{
		if (array_key_exists('callback', static::$rootMenu) === false) {
			return;
		}
		
		if (static::$rootMenu['callback'] === '') {
			static::setRootCallback();
		} else {
			
			$pageName = static::$rootMenu['callback'];
			static::setCallbackByPageName(static::$rootMenu['callback'], $pageName);
			
		}
	}
	
	private static function setRootCallback(){
		static::$rootMenu['callback'] = function () {
			return static::setRootMenuCallback();
		};
	}
	
	private static function setCallbackByPageName(&$menuItem, $pageName)
	{
		$menuItem = function () use ($pageName) {
			
			$pageObject = new $pageName();
			return $pageObject->render();
			
		};
	}
	
	private static function setCallbacksForSubmenuItems($hidden = false)
	{
		$menus = $hidden ? static::$hiddenMenus : static::$submenus;
		
		foreach ($menus as $slug => $settings) {
			
			if ($settings['callback'] === '') {
				static::setSubmenuCallback($slug, $hidden);
			} else {
				
				$pageName = $settings['callback'];
				
				if ($hidden) {
					static::setCallbackByPageName(static::$hiddenMenus[$slug]['callback'], $pageName);
				} else {
					static::setCallbackByPageName(static::$submenus[$slug]['callback'], $pageName);
				}
				
			}
			
		}
	}
	
	private static function setSubmenuCallback($slug, $hidden = false)
	{
		$callback = function () use($slug) {
			
			$cleanSlug = kababToCamel($slug, true);
			
			$methodName = 'handleSet' . $cleanSlug . 'Callback';
			static::{$methodName}();
			
		};
		
		if ($hidden) {
			static::$hiddenMenus[$slug]['callback'] = $callback;
		} else {
			static::$submenus[$slug]['callback'] = $callback;
		}
	}
	
	private static function addRootSettingsMenu()
	{
		$rootMenu	= array_merge(static::$rootMenuDefault, static::$rootMenu);
		$pageTitle		= __($rootMenu['page-title'], Config::$namespace);
		$rootMenuText	= __($rootMenu['menu-text'], Config::$namespace);
		
		if (!empty($rootMenu['custom-icon'])) {
			$rootMenu['icon'] = Config::$pluginBaseUrl . 'assets/img/' . $rootMenu['custom-icon'];
		}
		
		add_menu_page(
			$pageTitle,
			$rootMenuText,
			$rootMenu['capabilities'],
			$rootMenu['slug'],
			$rootMenu['callback'],
			$rootMenu['icon']
		);
	}
	
	private static function addSubmenus()
	{
		foreach (static::$submenus as $pageData) {
			static::addPage($pageData);
		}
	}
	
	private static function addPage(array $pageData, $hidden = false)
	{
		$pageData = array_merge(static::$submenuDefault, $pageData);
		$parentSlug = $hidden ? null : static::$rootMenu['slug'];
		
		add_submenu_page(
			$parentSlug,
			$pageData['page-title'],
			$pageData['menu-text'],
			$pageData['capabilities'],
			$pageData['slug'],
			$pageData['callback']
		);
	}
	
	private static function addHiddenPages()
	{
		foreach (static::$hiddenMenus as $slug => $pageData) {
			static::addPage($pageData, true);
		}
	}
}
