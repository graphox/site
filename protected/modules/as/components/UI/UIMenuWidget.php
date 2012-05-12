<?php

class UIMenuWidget extends AsWidget
{
	public $view = 'as.views.UI.menuwidget';
	public $menu = array();
	
	public $render = true;
	
	function run()
	{
		$result = $this->render($this->view, array('menu' => $this->menu), true);
		
		if($this->render)
			echo $result;
		
		return $result;
	}
}
