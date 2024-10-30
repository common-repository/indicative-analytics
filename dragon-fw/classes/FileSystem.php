<?php

namespace Dragon;

class FileSystem
{
	protected static $pluginClassNameToFile = [
		// static::class => 'path/from/plugin/root/to/its/class.php',
	];
	
	protected static $classToFile = [
		//WP_List_Table::class => 'wp-admin/includes/class-wp-list-table.php',
	];
	
	public static function includeClasses()
	{
		foreach (static::$classToFile as $className => $relativePath) {
			
			$filePath = ABSPATH . $relativePath;
			return static::includeClassFile($className, $filePath);
			
		}
	}
	
	public static function includeClass($className, $pluginName)
	{
		$relativePath = static::$pluginClassNameToFile[$className];
		$filePath = static::getPluginPath($pluginName) . $relativePath;
		return static::includeClassFile($className, $filePath);
	}
	
	public static function isPluginActive($pluginPath)
	{
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		
		return is_plugin_active($pluginPath);
	}
	
	public static function getCurrentPageSlug()
	{
		$homeURL = home_url('/');
		$currentURL = (string) @get_page_link();
		$currentPath = substr($currentURL, strlen($homeURL));
		$currentPathWithoutTrailingSlash = substr($currentPath, 0, strlen($currentPath)-1);
		
		return $currentPathWithoutTrailingSlash;
	}
	
	public static function defineInWpConfig($variable, $value)
	{
		if (static::stringExistsInWpConfig($variable) === false) {
			
			$wpConfig = file_get_contents(ABSPATH . 'wp-config.php');
			$define = "<" . "?php\ndefine('" . $variable . "', '" . $value . "');\n";
			
			$newWpConfig = str_replace('<' . '?php', $define, $wpConfig);
			file_put_contents(ABSPATH . 'wp-config.php', $newWpConfig);
			
			@define($variable, $value);
			
		}
	}
	
	public static function stringExistsInWpConfig($string)
	{
		$wpConfig = file_get_contents(ABSPATH . 'wp-config.php');
		return strpos($wpConfig, $string) !== false;
	}
	
	public static function loadScripts(array $scripts, $allowAjaxOnFrontEnd = true)
	{
		foreach ($scripts as $handle => $script) {
			wp_enqueue_script($handle, Config::$pluginBaseUrl . 'assets/js/' . $script, array('jquery'), true, true);
			
			if ($allowAjaxOnFrontEnd) {
				wp_localize_script($handle, 'ajax_url', admin_url('admin-ajax.php'));
			}
			
		}
	}
	
	public static function loadScriptUrls(array $scriptUrls)
	{
		foreach ($scriptUrls as $handle => $script) {
			wp_enqueue_script($handle, $script, array('jquery'), true, true);
		}
	}
	
	public static function loadCss(array $cssFiles)
	{
		foreach ($cssFiles as $handle => $css) {
			wp_enqueue_style($handle, Config::$pluginBaseUrl . 'assets/css/' . $css, [], true);
		}
	}
	
	public static function loadCssUrls(array $cssUrls)
	{
		foreach ($cssUrls as $handle => $css) {
			wp_enqueue_style($handle, $css, [], true);
		}
	}
	
	public static function urlToPath($url)
	{
		$wpRootUrl = get_bloginfo('url') . '/';
		$wpRootDirectory = ABSPATH;
		
		return str_replace($wpRootUrl, $wpRootDirectory, $url);
	}
	
	public static function getUrlForPlugin($pluginDirectory)
	{
		$wpRootUrl = get_bloginfo('url') . '/';
		$wpRootDirectory = ABSPATH;
		$pluginPath = static::getPluginPath($pluginDirectory);
		
		return str_replace($wpRootDirectory, $wpRootUrl, $pluginPath);
	}
	
	public static function getMimeType($filename)
	{
		return mime_content_type($filename);
	}
	
	public static function getLanguagePage($pluginDirectory = null)
	{
		$pluginDirectory = $pluginDirectory === null ? Config::$pluginName : $pluginDirectory;
		$pluginPath = static::getPluginPath($pluginDirectory);
		return $pluginPath . 'assets/lang/';
	}
	
	public static function saveBase64AsImageFile($data, $path, $name, $extension = null)
	{
		list($type, $image)	= explode(';', $data);
		list(, $mimeType)	= explode(':', $type);
		list(, $possibleExt)	= explode('/', $mimeType);
		list(, $imageData)	= explode(',', $data);
		$contents = base64_decode($imageData);
		
		$isImage = $extension !== null || in_array($possibleExt, ['png', 'jpg', 'gif', 'jpeg', 'bmp']);
		
		if ($isImage) {
			
			$extension = is_null($extension) ? $possibleExt : $extension;
			$file = $name . '.' . $extension;
			$fileFullPath = $path . $file;
			file_put_contents($fileFullPath, $contents);
			return $file;
			
		}
		
		return null;
	}
	
	private static function includeFunctionsFor(array $functionList, $areaUrl)
	{
		$basePath = static::urlToPath($areaUrl);
		
		foreach ($functionList as $functionName => $relativePath) {
			
			$filePath = $basePath . $relativePath;
			static::includeFunction($functionName, $filePath);
			
		}
	}
	
	private static function includeFunction($functionName, $filePath)
	{
		if (!function_exists($functionName) && file_exists($filePath)) {
			require_once($filePath);
		}
	}
	
	private static function includeClassFile($className, $filePath)
	{
		if (!class_exists($className) && file_exists($filePath)) {
			require_once($filePath);
		}
	}
	
	private static function getPluginPath($pluginDirectory)
	{
		return WP_PLUGIN_DIR . '/' . $pluginDirectory . '/';
	}
}
