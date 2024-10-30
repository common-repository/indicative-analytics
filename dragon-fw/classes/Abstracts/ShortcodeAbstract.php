<?php

namespace Dragon\Abstracts;

abstract class ShortcodeAbstract
{
	protected static $namespace = '';
	protected static $shortcodes = [];
	
	public static function createShortcodes()
	{
		foreach (static::$shortcodes as $tag) {
			
			add_shortcode($tag, function ($attributes) use ($tag) {
				
				$attributes = empty($attributes) ? [] : $attributes;
				
				$callback = $tag . 'Shortcode';
				return call_user_func([static::class, $callback], $attributes);
				
			});
			
		}
	}
	
	public static function createPage($tag, $component, array $attributes)
	{
		$className = static::$namespace . '\\' . ucfirst($component) . 'Page';
		
		if (class_exists($className)) {
			
			$componentPage = new $className($attributes);
			return $componentPage->render();
			
		}
	}
}
