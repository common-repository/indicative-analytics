<?php

use Dragon\Config;
use IndicativeWp\AdminPluginHooks;
use IndicativeWp\FrontEndPluginHooks;
use Dragon\Ignite;

/**
 * @package Indicative Analytics
 * 
 * Plugin Name: Indicative Analytics
 * Description: Installs the Indicative code snippet on your website. Start analyzing and optimizing your customer conversion, engagement, and retention with just one click. Install Indicative to your site to begin gaining actionable user insights into your customer journey.
 * Version: 1.8
 * Author: Indicative, Inc.
 * Author URI: https://www.indicative.com/?utm_source=partners&utm_medium=integration&utm_campaign=wordpressplugin
 * Text Domain: indicative
 * License: Proprietary
 **/

require_once('autoloader.php');

Config::$namespace = 'indicative';
Config::$pluginName = 'indicative';
Config::$pluginBaseUrl = plugin_dir_url(__FILE__);
Config::$pluginLoaderFile = __FILE__;
Config::$pluginDir = __DIR__;

Ignite::fire();

if (is_admin()) {
	
	register_activation_hook(Config::$pluginLoaderFile, array(AdminPluginHooks::class, 'onActivation'));
	register_deactivation_hook(Config::$pluginLoaderFile, array(AdminPluginHooks::class, 'onDeactivation'));
	
	add_action('init', array(AdminPluginHooks::class, 'init'));
	
} else {
	add_action('init', array(FrontEndPluginHooks::class, 'init'));
}

if (!function_exists('do_output_buffer')) {
	
	function do_output_buffer() {
		ob_start();
	}
	
	add_action('init', 'do_output_buffer');
	
}
