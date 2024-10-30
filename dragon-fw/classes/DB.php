<?php

namespace Dragon;

use Illuminate\Database\Capsule\Manager as Capsule;

class DB
{
	private $multisiteNonPrefixedTables = [
		'blogmeta', 'blogs', 'blog_versions',
		'registration_log', 'signups', 'site',
		'sitemeta', 'usermeta', 'users',
	];
	
	private static $instance = null;
	private $migrationDir = null;
	
	public static function make()
	{
		if (static::$instance === null) {
			static::$instance = new static();
		}
		
		return static::$instance;
	}
	
	public function migrate()
	{
		$this->iterateMigrations(function ($migrationClass) {
			
			if ($this->hasTable($migrationClass->tableName) === false) {
				$migrationClass->up();
			}
			
		});
	}
	
	public function rollback()
	{
		$this->iterateMigrations(function ($migrationClass) {
			
			if ($this->hasTable($migrationClass->tableName)) {
				$migrationClass->down();
			}
			
		});
	}
	
	public function hasTable($tableName)
	{
		return Capsule::schema()->hasTable($tableName);
	}
	
	private function __construct()
	{
		global $wpdb;
		$capsule = new Capsule();
		
		$prefix = is_multisite() ? str_replace($wpdb->blogid . '_', '', $wpdb->prefix) : $wpdb->prefix;
		
		$capsule->addConnection([
			"driver"	=> "mysql",
			"host"		=> DB_HOST,
			"database"	=> DB_NAME,
			"username"	=> DB_USER,
			"password"	=> DB_PASSWORD,
			"prefix"    => $prefix,
		]);
		
		$capsule->setAsGlobal();
		$capsule->bootEloquent();
		
		$this->setMigrationsDirectory();
	}
	
	private function setMigrationsDirectory()
	{
		$pluginFile = Config::$pluginLoaderFile;
		$this->migrationDir = dirname($pluginFile) . '/migrations/';
	}
	
	private function iterateMigrations(Callable $callback)
	{
		$dir = dir($this->migrationDir);
		while (false !== ($entry = $dir->read())) {
			
			if (in_array($entry, ['.', '..']) || strpos($entry, '.php') === false) {
				continue;
			}
			
			$filename = $this->migrationDir . $entry;
			require_once($filename);
			$classname = str_replace(['_', '.php'], '', $entry);
			
			$callback(new $classname());
			
		}
		
		$dir->close();
	}
}
