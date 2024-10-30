<?php

namespace Dragon;

use Carbon\Carbon;

class Log
{
	public static function info($data)
	{
		static::makePrintable($data);
		$logFilename = realpath(__DIR__ . '/../../');
		$dateTime = '[' . Carbon::now()->toDateTimeString() . ']';
		file_put_contents($logFilename . '/plugin.log', $dateTime . ' ' . $data . "\n\n", FILE_APPEND);
	}
	
	private static function makePrintable(&$data)
	{
		$data = is_array($data) ? print_r($data, true) : $data;
		$data = is_null($data) ? 'NULL' : $data;
		$data = ($data === '') ? 'string(0)' : $data;
		$data = is_object($data) ? 'object(' . get_class($data) . ')' : $data;
		
		if (is_bool($data)) {
			$data = ($data === true) ? 'bool(true)' : 'bool(false)';
		}
	}
}
