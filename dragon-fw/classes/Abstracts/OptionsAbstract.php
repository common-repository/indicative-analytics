<?php

namespace Dragon\Abstracts;

use Dragon\Config;
use Dragon\View;

class OptionsInvalidNonceException extends \Exception{}
class OptionsMissingRequiredFieldsException extends \Exception{}

abstract class OptionsAbstract extends PageAbstract
{
	protected $saveButton = null;
	protected $adminForm = true;
	protected $requiredFields = [];
	protected $optionalFields = [];
	protected $encryptedFields = [];
	protected $successMessage = 'All settings saved successfully.';
	protected $badNonceMessage = 'An invalid nonce was supplied for the form. Try refreshing the page and reentering your information.';
	protected $missingRequiredMessage = 'One or more required fields are missing. Please enter the missing information and try again.';
	protected $genericError = 'An error occurred.';
	protected $pageData = [];
	
	protected function saveSettings(&$pageData)
	{
		if (is_null($this->saveButton)) {
			$this->saveButton = Config::$namespace . '_save';
		}
		
		if (!empty($_POST[$this->saveButton])) {
			try {
				
				$this->handleSaveSettings();
				if (empty($pageData['notice'])) {
					$pageData['notice'] = $this->getSaveSuccessNotice();
				}
				
			} catch (OptionsInvalidNonceException $e) {
				$pageData['notice'] = $this->getBadNonceNotice();
			} catch (OptionsMissingRequiredFieldsException $e) {
				$pageData['notice'] = $this->getMissingRequiredFieldsNotice();
			} catch (\Exception $e) {
				$pageData['notice'] = View::getNotice('notice-error', $e->getMessage());
			}
		}
	}
	
	protected function updateOptionIfSet($option)
	{
		if (array_key_exists($option, $_POST) === true) {
			
			$value = in_array($option, $this->encryptedFields) ? dragonEncrypt($_POST[$option]) : $_POST[$option];
			update_option($option, sanitize_text_field($value));
			
		}
	}
	
	protected function save()
	{
		foreach($this->requiredFields as $field) {
			$this->updateOptionIfSet($field);
		}
		
		foreach($this->optionalFields as $field) {
			$this->updateOptionIfSet($field);
		}
	}
	
	private function handleSaveSettings()
	{
		$this->throwIfMissingRequiredFields();
		$this->throwIfBadNonce(Config::$namespace . "_nonce");
		
		$this->save();
	}
	
	private function throwIfMissingRequiredFields()
	{
		if ($this->areAllRequiredFieldsPresent() === false) {
			throw new OptionsMissingRequiredFieldsException();
		}
	}
	
	private function areAllRequiredFieldsPresent()
	{
		foreach ($this->requiredFields as $field) {
			if (array_key_exists($field, $_POST) === false || $_POST[$field] === '') {
				return false;
			}
		}
		return true;
	}
	
	private function throwIfBadNonce($pageNamespace)
	{
		if ($this->adminForm === true && $this->isValidNonce($pageNamespace) === false) {
			throw new OptionsInvalidNonceException();
		}
	}
	
	private function isValidNonce($pageNamespace)
	{
		return wp_verify_nonce($_POST[$pageNamespace], $pageNamespace);
	}
	
	private function getSaveSuccessNotice()
	{
		return View::getNotice('notice-success', __($this->successMessage, Config::$namespace));
	}
	
	private function getBadNonceNotice()
	{
		return View::getNotice('notice-error', __($this->badNonceMessage, Config::$namespace));
	}
	
	private function getMissingRequiredFieldsNotice()
	{
		return View::getNotice('notice-error', __($this->missingRequiredMessage, Config::$namespace));
	}
}
