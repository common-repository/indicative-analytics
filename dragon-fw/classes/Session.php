<?php

namespace Dragon;

class Session
{
	public static function get($key, $default = null)
	{
		return array_key_exists(static::key($key), $_SESSION) ? $_SESSION[static::key($key)] : $default;
	}
	
	public static function set($key, $val)
	{
		$_SESSION[static::key($key)] = $val;
	}
	
	public static function delete($key)
	{
		if (array_key_exists(static::key($key), $_SESSION)) {
			unset($_SESSION[static::key($key)]);
		}
	}
	
	public static function flash($key, $value)
	{
		static::set(static::flashKey($key), $value);
	}
	
	public static function getFlash($key, $default)
	{
		$value = static::get(static::flashKey($key), $default);
		static::delete(static::flashKey($key));
		return $value;
	}
	
	public static function getForPage($key, $default)
	{
		$keyWithPage = static::getPageKey() . '.' . static::key($key);
		return static::get($keyWithPage, $default);
	}
	
	public static function setForPage($key, $val)
	{
		$keyWithPage = static::getPageKey() . '.' . static::key($key);
		static::set($keyWithPage, $val);
	}
	
	public static function deleteForPage($key)
	{
		$keyWithPage = static::getPageKey() . '.' . static::key($key);
		static::delete($keyWithPage);
	}
	
	private static function flashKey($key)
	{
		return 'flash.' . Config::$namespace . $key;
	}
	
	private static function key($key)
	{
		return Config::$namespace . $key;
	}
	
	private static function getPageKey()
	{
		return FileSystem::getCurrentPageSlug();
	}
}
