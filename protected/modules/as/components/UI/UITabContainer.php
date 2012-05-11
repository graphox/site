<?php

class UITabContainer extends AsWidget
{
	public $tabContainerView = 'as.views.UI.tabcontainer';
	public $tabElementView = 'as.views.UI.tabelement';

	public $title;
	
	public $render = true;
	
	private $tabs;
	
	private $_tabname;
	
	function init()
	{
		if(!isset($this->title)) throw new Exception('Required argument not set: title');
	}
	
	function run()
	{
		$result = $this->render($this->tabContainerView, array('tabs' => $this->tabs, 'title' => $this->title), true);
		
		if($this->render)
			echo $result;
		
		return $result;
	}
	
	function add_tab($name, $content)
	{
		$this->tabs[] = (object)array(
			'content' => $this->render($this->tabElementView, array('name' => $name, 'content' => $content), true),
			'name' => $name			
		);
	}
	
	function start_tab($name)
	{
		$this->_tabname = $name;
		
		ob_start();
		ob_implicit_flush(false);
	}
	
	function end_tab()
	{
		return $this->add_tab($this->_tabname, ob_get_clean());
	}
}
