<?php

namespace Dragon;

class Lang
{
	public static function get($fileDot, $key, $default = null)
	{
		$langArray = static::getArrayByFileDot($fileDot);
		return arrayDot($langArray, $key, $default);
	}
	
	private static function getArrayByFileDot($fileDot)
	{
		$langRoot = FileSystem::getLanguagePage();
		
		$pathData = explode('.', $fileDot);
		for ($i = 0; $i < count($pathData); $i++) {
			
			if ($i === count($pathData)-1) {
				
				$file = $langRoot . $pathData[$i] . '.php';
				if (file_exists($file)) {
					return require($file);
				} else {
					return [];
				}
				
			} else {
				$langRoot .= $pathData[$i] . '/';
			}
			
		}
	}
}
