<?php

namespace Dragon\Abstracts;

abstract class PluginHooksAbstract
{
	protected static $actions = [
		//
	];
	
	protected static $filters = [
		//
	];
	
	public static function init()
	{
		static::setActions();
		static::setFilters();
	}
	
	protected static function setActions()
	{
		static::setHooks(static::$actions, 'add_action');
	}
	
	protected static function setFilters()
	{
		static::setHooks(static::$filters, 'add_filter');
	}
	
	private static function setHooks(array &$hooks, $callbackName)
	{
		foreach ($hooks as $hook => $params) {
			
			$callback = empty($params['callback']) ? $params : $params['callback'];
			$priority = empty($params['priority']) ? 10 : $params['priority'];
			$args = empty($params['args']) ? 1 : $params['args'];
			call_user_func($callbackName, $hook, $callback, $priority, $args);
			
		}
	}
}
