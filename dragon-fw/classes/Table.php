<?php

namespace Dragon;

abstract class Table
{
	protected $noItems = "No items to display";
	protected $pageTitle = "";
	
	protected $headers = [
		'Title',
		'Comments',
		'Author',
	];
	
	protected $createLink = "";
	protected $createLinkText = "";
	
	protected $rows = [
		[
			[
				'data'	=> 'Testing',
				'links'	=> [
					'view'	=> [
						'link'	=> '#',
					],
					'edit'	=> [
						'link'	=> '#',
						'class'	=> 'my-edit',
					],
					'delete'	=> [
						'link'	=> '#',
					],
				],
			],
			[
				'data'	=> 'Testing 1',
			],
			[
				'data'	=> 'Testing 2',
			],
		],
	];
	
	protected $pageData = [];
	
	public function render()
	{
		$this->pageData = array_merge([
			'create-link'	=> $this->createLink,
			'create-link-text'	=> $this->createLinkText,
			'headers'		=> $this->headers,
			'rows'			=> $this->rows,
			'no-items'		=> $this->noItems,
			'page-title'	=> $this->pageTitle,
		], $this->pageData);
		
		View::displayPage('AdminTable', $this->pageData);
	}
}
