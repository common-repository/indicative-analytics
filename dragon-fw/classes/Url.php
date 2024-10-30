<?php

namespace Dragon;

class Url
{
	public static function getBySlug($page)
	{
		$page = get_page_by_path($page);
		return get_permalink($page);
	}
	
	public static function getAdminMenuLink($slug)
	{
		$link = menu_page_url($slug, false);
		if (empty($link)) {
			return static::getDomain() . '/wp-admin/admin.php?page=' . $slug;
		}
		
		return $link;
	}
	
	public static function getCurrentUrl()
	{
		return static::getDomain() . $_SERVER['REQUEST_URI'];
	}
	
	public static function getDomain()
	{
		$protocol = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on" ? "http" : "https";
		
		return $protocol . "://" .$_SERVER['HTTP_HOST'];
	}
}
