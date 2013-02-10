<?php

namespace Graphox\Modules\Server;

class ServerModule extends \CWebModule
{
	public $controllerNamespace = '\Graphox\Modules\Server\Controllers';
	
	public function getFetcher()
	{
		static $instance;
		
		if(!isset($instance))
		{
			$instance = new \AsSauerQuery;
		}
		
		return $instance;
	}

	public function init()
	{

	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
}
