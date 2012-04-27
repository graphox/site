<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class PageForm extends CFormModel
{
	public $uri;
	public $title;
	public $content;
	public $date;
	public $makeup = 'plain';
	public $public = 1;

	function __construct($a = null, $b = null, $c = null)
	{
		$this->date = new CDbExpression('NOW()');
		parent::__construct($a, $b, $c);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// required fields
			array('uri, title, content, date', 'required', 'on' => 'add'),
			array('public', 'boolean', 'on' => 'add'),
			array('uri, title, content', 'length', 'min' => 3, 'on' => 'add'),
			array('uri, title', 'length', 'max' => 50, 'on' => 'add'),
		);
	}
	
	public function save()
	{
		$page = new Page;
		$page->uri = $this->uri;
		$page->title = $this->title;
		$page->content = $this->content;
		$page->date = $this->date;
		$page->makeup = $this->makeup;
		$page->public = $this->public;
		$page->save();
		
		return $page->id;
	}
}
