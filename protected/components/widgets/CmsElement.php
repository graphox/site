<?php

class CmsElement extends CWidget
{
	public $template;
	public $name;
	
	public function run()
	{
		echo str_replace('{content}', 'This is a '.$this->name, $this->template);
	}
}
