<?php

namespace Dragon;

use Dragon\Abstracts\PluginHooksAbstract;

class Cron extends PluginHooksAbstract
{
	const SCHEDULE_HOURLY = 'hourly';
	const SCHEDULE_DAILY = 'daily';
	const SCHEDULE_TWICE_DAILY = 'twicedaily';
	
	public static function scheduleCron($hook, $timing, $firstRun = 'midnight')
	{
		if (!wp_next_scheduled($hook)) {
			wp_schedule_event(static::timestamp($firstRun), $timing, $hook);
		}
	}
	
	public static function unscheduleCron($hook)
	{
		wp_clear_scheduled_hook($hook);
	}
	
	private static function timestamp($firstRun)
	{
		if (is_int($firstRun)) {
			return $firstRun;
		}
		
		$dateTime = new \Datetime($firstRun, new \Datetimezone('UTC'));
		return $dateTime->getTimestamp();
	}
}
