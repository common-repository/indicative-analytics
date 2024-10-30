<?php

namespace Dragon;

class Cache
{
	public static function set($key, $value, $expirationTimeStamp = null)
	{
		$cacheThis = [
			'expires'	=> $expirationTimeStamp,
			'data'		=> $value,
		];
		
		$cacheKey = static::getNamespacedCacheKey($key);
		update_option($cacheKey, $cacheThis);
	}
	
	public static function get($key, $default = null)
	{
		$cacheKey = static::getNamespacedCacheKey($key);
		
		$cachedData = get_option($cacheKey, null);
		if (!is_array($cachedData)) {
			return $default;
		}
		
		$expires = $cachedData['expires'];
		if ($expires !== null && (int)$expires < time()) {
			return $default;
		}
		
		return $cachedData['data'];
	}
	
	public static function delete($key)
	{
		$cacheKey = static::getNamespacedCacheKey($key);
		delete_option($cacheKey);
	}
		
	private static function getNamespacedCacheKey($key)
	{
		return $cacheKey = 'dragon-cache.' . Config::$namespace . '.' . $key;
	}
}
