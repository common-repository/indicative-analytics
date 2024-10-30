<?php

namespace Dragon;

use Illuminate\Filesystem\Filesystem as LaravelFilesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class ValidatorFactory
{
	private $factory;
	
	public function __construct()
	{
		$this->factory = new Factory(
			$this->loadTranslator()
		);
	}
	
	protected function loadTranslator()
	{
		$translationPath = realpath('../../assets/lang');
		
		$filesystem = new LaravelFilesystem();
		$loader = new FileLoader($filesystem, $translationPath);
		$loader->addNamespace('lang', $translationPath);
		$loader->load('en', 'validation', 'lang');
		
		return new Translator($loader, 'en');
	}
	
	public function __call($method, $args)
	{
		return call_user_func_array(
			[$this->factory, $method],
			$args
		);
	}
}
