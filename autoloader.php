<?php

require_once('dragon-fw/autoloader.php');
require_once('vendor/autoload.php');

spl_autoload_register(function ($className)
{
	$ns = 'IndicativeWp\\';
	if (strpos($className, $ns) !== false) {

		$noNamespace = str_replace($ns, '', $className);
		$normalizedClassName = str_replace('\\', '/', $noNamespace);
		$fullPathFile = __DIR__ . '/classes/' . $normalizedClassName . '.php';

		if (($realPath = realpath($fullPathFile)) !== false) {
			require_once($realPath);
		}

	}
});

require_once('helpers.php');
