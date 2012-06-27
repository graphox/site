<?php

class UIContentWidget extends AsWidget
{
	public $markup = 'plain';
	
	public $render = true;
	
	public function init()
	{
		parent::init();

		ob_start();
		ob_implicit_flush(false);
	}	
	
	public function run()
	{
		$result = ob_get_clean();
		
		ContentMakeup::parse($result, $this->markup);
		
		if($this->render)
			echo $result;
		
		return $result;
	}
}
