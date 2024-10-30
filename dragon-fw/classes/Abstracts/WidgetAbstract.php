<?php

namespace Dragon\Abstracts;

abstract class WidgetAbstract extends \WP_Widget
{
	protected static $widgetTag = '';
	protected static $widgetName = '';
	protected static $widgetDescription = '';
	
	protected $widgetFields = [];
	
	public function __construct()
	{
		parent::__construct(static::$widgetTag, static::$widgetName, [
			'description' => static::$widgetDescription
		]);
	}
	
	public static function registerWidget()
	{
		register_widget(static::class);
	}
	
	public function update($newInstance, $oldInstance)
	{
		$instance = [];
		
		foreach ($this->widgetFields as $fieldName) {
			$instance[$fieldName] = strip_tags($newInstance[$fieldName]);
		}
		
		return $instance;
	}
	
	protected function fillValues(array $fields)
	{
		$defaults = array_fill_keys($this->widgetFields, '');
		return array_merge($defaults, $fields);
	}
}
