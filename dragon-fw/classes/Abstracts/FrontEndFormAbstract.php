<?php

namespace Dragon\Abstracts;

use Dragon\Config;

class FrontEndFormInvalidNonceException extends \Exception{}
class FrontEndFormMissingRequiredFieldsException extends \Exception{}

abstract class FrontEndFormAbstract extends PageAbstract
{
	protected $pageData = [];
	protected $button = 'dragon_submit';
	protected $requiredFields = [];
	protected $badNonceMessage = 'An invalid nonce was supplied for the form. Try refreshing the page and reentering your information.';
	protected $missingRequiredMessage = 'One or more required fields are missing. Please enter the missing information and try again.';
	protected $genericError = 'An error occurred.';
	
	protected function handleSubmission()
	{
		if (!empty($_POST[$this->button])) {
			try {
				
				$this->throwIfBadNonce(Config::$namespace . "_nonce");
				$this->throwIfMissingRequiredFields();
				return true;
				
			} catch (FrontEndFormInvalidNonceException $e) {
				
				$this->handleBadNonce();
				return false;
				
			} catch (FrontEndFormMissingRequiredFieldsException $e) {
				
				$this->handleMissingRequired();
				return false;
				
			}
		}
	}
	
	private function throwIfMissingRequiredFields()
	{
		if ($this->areAllRequiredFieldsPresent() === false) {
			throw new FrontEndFormMissingRequiredFieldsException();
		}
	}
	
	private function areAllRequiredFieldsPresent()
	{
		foreach ($this->requiredFields as $field) {
			
			$postNotSet = array_key_exists($field, $_POST) === false || $_POST[$field] === '';
			$fileNotSet = array_key_exists($field, $_FILES) === false || $_FILES[$field]['tmp_name'] === '';
			
			if ($postNotSet && $fileNotSet) {
				return false;
			}
			
		}
		
		return true;
	}
	
	private function throwIfBadNonce($pageNamespace)
	{
		if ($this->isValidNonce($pageNamespace) === false) {
			throw new FrontEndFormInvalidNonceException();
		}
	}
	
	private function isValidNonce($pageNamespace)
	{
		return empty($_POST[$pageNamespace]) ? false : wp_verify_nonce($_POST[$pageNamespace], $pageNamespace);
	}
	
	protected function handleBadNonce()
	{
		$this->pageData['notice'] = $this->badNonceMessage;
	}
	
	protected function handleMissingRequired()
	{
		$this->pageData['notice'] = $this->missingRequiredMessage;
	}
}
