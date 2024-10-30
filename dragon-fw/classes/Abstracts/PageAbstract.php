<?php

namespace Dragon\Abstracts;

use Dragon\Config;
use Dragon\View;

abstract class PageAbstract
{
	protected function makeNotice($type, $message)
	{
		return View::getNotice($type, __($message, Config::$namespace));
	}
	
	abstract public function render();
}
