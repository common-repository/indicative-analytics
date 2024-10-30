<?php

namespace IndicativeWp;

use Dragon\FrontEndPluginHooks as DragonFrontEndPluginHooks;
use Dragon\FileSystem;

class FrontEndPluginHooks extends DragonFrontEndPluginHooks
{
	protected static $actions = [
		'wp_login'		=> [
			'callback'	=> [FrontEndPluginHooks::class, 'assignMembershipToUser'],
			'args'	=> 2,
		],
		'wp_head'	=> [FrontEndPluginHooks::class, 'maybeAddSnippetToHeader'],
		'wp_footer'	=> [FrontEndPluginHooks::class, 'maybeAddSnippetToFooter'],
	];
	
	protected static $filters = [];
	
	public static function init()
	{
		parent::init();
		
		if (get_option('indicative_track_link_clicks', 'yes') === 'yes') {
			
			FileSystem::loadScripts([
				'indicative-link-tracker'	=> 'link-tracker.js',
			]);
			
		}
	}
	
	public static function maybeAddSnippetToHeader()
	{
		static::displaySnippetIf('head');
	}
	
	public static function maybeAddSnippetToFooter()
	{
		static::displaySnippetIf('body');
	}
	
	private static function displaySnippetIf($placement)
	{
		$shouldDisplay = get_option('indicative_code_snippet_placement', 'body') === $placement;
		if ($shouldDisplay === false) {
			return;
		}
		
		Indicative::displaySnippet();
	}
}
