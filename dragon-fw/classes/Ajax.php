<?php

namespace Dragon;

class Ajax
{
	protected static function createHook($hook, Callable $callback, $includeNoPrivledges = false)
	{
		$ns = Config::$namespace;
		$hookName = $ns . '_' . $hook;
		
		add_action('wp_ajax_' . $hookName, function () use ($callback) {
			static::doCallback($callback);
		});
		
		if ($includeNoPrivledges) {
			add_action('wp_ajax_nopriv_' . $hookName, function () use ($callback) {
				static::doCallback($callback);
			});
		}
	}
	
	private static function doCallback(Callable $callback)
	{
		$response = $callback();
		if (is_array($response) || is_object($response)) {
			echo json_encode($response);
		} else {
			echo '{}';
		}
		
		wp_die();
	}
}
