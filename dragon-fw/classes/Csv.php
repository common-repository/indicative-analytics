<?php

namespace Dragon;

class Csv
{
	public $headers = [];
	public $rows = [];
	
	private $filename = null;
	private $csvMimes = [
		'application/vnd.ms-excel',
		'text/plain',
		'text/csv',
		'text/tsv',
	];
	
	public function __construct($filename)
	{
		$this->filename = $filename;
		
		if ($this->isCsv()) {
			$this->parse();
		}
	}
	
	public function isCsv()
	{
		if (!in_array(FileSystem::getMimeType($this->filename), $this->csvMimes)) {
			return false;
		}
		
		return true;
	}
	
	private function parse()
	{
		$handle = fopen($this->filename, "r");
		if ($handle !== false) {
			
			while (($data = fgetcsv($handle)) !== false) {
				
				if (empty($this->headers)) {
					$this->headers = $data;
				} else {
					$this->rows[] = $data;
				}
				
			}
			
			fclose($handle);
			
		}
	}
}
