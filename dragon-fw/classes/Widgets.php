<?php

namespace Dragon;

class Widgets
{
	protected static $widgets = [];
	
	public static function registerWidgets()
	{
		foreach (static::$widgets as $widget) {
			add_action('widgets_init', [$widget, 'registerWidget']);
		}
	}
}
