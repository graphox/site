<?php

class UIBreadcrumbContainer extends AsWidget
{
	public $view = 'as.views.UI.breadcrumbs';
	public $breadcrumbs = array();
	
	public $render = true;
	
	function run()
	{
		$result = $this->render($this->view, array('breadcrumbs' => $this->breadcrumbs), true);
		
		if($this->render)
			echo $result;
		
		return $result;
	}
}
