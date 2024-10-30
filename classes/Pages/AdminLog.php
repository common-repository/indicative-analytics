<?php

namespace IndicativeWp\Pages;

use Dragon\Config;
use Dragon\View;
use Dragon\Abstracts\PageAbstract;

class AdminLog extends PageAbstract
{
	public function render()
	{
		$this->handleClear();
		
		$pageData['log'] = file_get_contents(Config::$pluginDir . '/plugin.log');
		View::displayPage('AdminLog', $pageData);
	}
	
	private function handleClear()
	{
		if (!empty($_POST['indicative_clear_log'])) {
			file_put_contents(Config::$pluginDir . '/plugin.log', '');
		}
	}
}
