<?php

namespace Dragon;

class DropDown
{
	public static function create(array $options, $selectedValue = null)
	{
		$optionsHtml = '';
		foreach ($options as $value => $text) {
			
			$selected = '';
			if ($selectedValue !== null && $selectedValue == $value) {
				$selected = 'selected';
			}
			
			$optionsHtml .= '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>';
			
		}
		
		return $optionsHtml;
	}
}
