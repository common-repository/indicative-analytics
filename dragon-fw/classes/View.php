<?php

namespace Dragon;

class View
{
	public static function displayPage($page, array $pageData = array())
	{
		$file = static::getFilenameForPage($page);
		if (!file_exists($file)) {
			echo __("Template not found", Config::$namespace);
		} else {
			require($file);
		}
	}
	
	public static function parsePage($page, array $pageData = array())
	{
		$callback = function () use ($page, $pageData) {
			View::displayPage($page, $pageData);
		};
		
		return static::collectOutput($callback);
	}
	
	public static function parseTemplate($page, array $pageData = array())
	{
		$file = static::getFilenameForPage($page);
		$page = file_get_contents($file);
		
		$template = array_keys($pageData);
		$replacements = array_values($pageData);
		$page = str_replace($template, $replacements, $page);
		
		return $page;
	}
	
	public static function getNotice($cssClass, $message)
	{
		return sprintf('<div class="notice %s"><p>%s</p></div>', $cssClass, __($message, Config::$namespace));
	}
	
	private static function collectOutput(Callable $callback)
	{
		$oldBuffer = ob_get_contents();
		ob_clean();
		$callback();
		$newContent = ob_get_contents();
		ob_clean();
		echo $oldBuffer;
		return $newContent;
	}
	
	private static function getViewPath()
	{
		return realpath(__DIR__ . '/../../views');
	}
	
	private static function getFilenameForPage($page)
	{
		$root = static::getViewPath();
		return $root . '/' . $page . '.php';
	}
}
