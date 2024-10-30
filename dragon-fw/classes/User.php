<?php

namespace Dragon;

class User
{
	const USER_KEY_BASE = 'dragon.user_data';
	
	public static function isLoggedIn()
	{
		$user = wp_get_current_user();
		return $user->exists();
	}
	
	public static function getUserId()
	{
		$user = wp_get_current_user();
		return $user->ID;
	}
	
	public static function getMeta($key, $default = null, $wasPosted = false, $userId = null)
	{
		$userId = empty($userId) ? static::getUserId() : $userId;
		$data = get_user_meta($userId, $key);
		
		if (is_array($data)) {
			
			if (count($data) === 1 && array_key_exists(0, $data)) {
				$data = $data[0];
			}
			
			if (empty($data)) {
				return $wasPosted && !empty($_POST[$key]) ? sanitize_text_field($_POST[$key]) : $default;
			}
			
		}
		
		return $data;
	}
	
	public static function setMeta($key, $value, $userId = null)
	{
		$userId = empty($userId) ? static::getUserId() : $userId;
		update_user_meta($userId, $key, $value);
	}
	
	public static function getUserIds(array $args = [])
	{
		$ids = [];
		$users = get_users($args);
		foreach ($users as $user) {
			$ids[] = $user->ID;
		}
		
		return $ids;
	}
	
	public static function redirectIfNotLoggedIn()
	{
		if (static::isLoggedIn() === false) {
			auth_redirect();
		}
	}
	
	public static function getUserOption($key, $default = null, $forcedUserId = false)
	{
		$key = static::getKeyNameForUser($key, $forcedUserId);
		return get_option($key, $default);
	}
	
	public static function setUserOption($key, $data, $forcedUserId = false)
	{
		$key = static::getKeyNameForUser($key, $forcedUserId);
		update_option($key, $data);
	}
	
	public static function loginAs($userId)
	{
		wp_set_auth_cookie($userId);
	}
	
	public static function register($username, $password, $email)
	{
		wp_create_user($username, $password, $email);
	}
	
	private static function getKeyNameForUser($key, $forcedUserId = false)
	{
		$userId = $forcedUserId !== false ? (int)$forcedUserId : static::getUserId();
		
		$keyName = static::USER_KEY_BASE . '.' . $userId . '.' . $key;
		return esc_sql($keyName);
	}
}
