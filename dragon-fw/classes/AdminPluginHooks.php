<?php

namespace Dragon;

use Dragon\Abstracts\PluginHooksAbstract;

class AdminPluginHooks extends PluginHooksAbstract
{
	public static function onActivation()
	{
		static::migrate();
	}
	
	public static function onDeactivation()
	{
		static::maybeRemoveTables();
	}
	
	protected static function migrate()
	{
		$db = DB::make();
		$db->migrate();
	}
	
	protected static function maybeRemoveTables()
	{
		if (get_option(Config::$namespace . '_remove_tables_on_deactivation', 'no') === 'yes') {
			
			$db = DB::make();
			$db->rollback();
			
		}
	}
}
