<?php

Yii::import('as.components.UI.*');

class DbMenuItem
{
	public $title = '';
	public $subtitle = '';
	
	public $children = array();
	
	public $url;
	
	public static function FromResult($result, $class = __CLASS__)
	{
		$new = new $class;
		
		$result->name = str_replace('\n', "\n", $result->name);
		
		$name = explode("\n", $result->name);
		
		if(isset($name[0]))
			$new->title = $name[0];
			
		if(isset($name[1]))
			$new->subtitle = $name[1];
		
		if(strstr($result->url, 'http://') === false)
			$new->url = array($result->url);
		else
			$new->url = $result->url;
		
		foreach($result->menuItems as $child)
			$new->children[] = $class::FromResult($child);
		
		return $new;	
	}
}

class UIDbMenuWidget extends UIMenuWidget
{
	public $elementView = 'as.views.UI.menuelement';
	public $containerView = 'as.views.UI.menucontainer';
	public $menu = 'main';
	
	public $render = true;
	
	function run()
	{
		$result = MenuItem::model()->findByAttributes(array( 'name' => $this->menu ));
		
		if(!$result)
			throw new exception('Could not find menu');
		
		$menu = DbMenuItem::FromResult($result);
		
		$result = $this->render($this->elementView, array('elements' => $menu->children, 'top_level' => true), true);
		
		$result = $this->render($this->containerView, array('elements' => $result), true);
		
		if($this->render)
			echo $result;
		
		return $result;
	}
}
