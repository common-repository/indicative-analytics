<?php

namespace Dragon;

class Ignite
{
	public static function fire()
	{
		setlocale(LC_MONETARY, 'en_US.UTF-8');
		
		FileSystem::includeClasses();
		
		DB::make();
	}
}
