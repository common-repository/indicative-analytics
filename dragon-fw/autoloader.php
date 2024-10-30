<?php
/**
 * The Dragon Framework is property of Dragon Cloud Inc.
 * @author https://dragoncloud.io
 */

spl_autoload_register(function ($className)
{
	$ns = 'Dragon\\';
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
