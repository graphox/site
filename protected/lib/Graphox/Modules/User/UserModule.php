<?php

namespace Graphox\Modules\User;

class UserModule extends \CWebModule
{
	private $services;
	private $popup;
	
	public $controllerNamespace = '\User\Controllers';
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'user.models.*',
			'user.components.*',
		));
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
	
	public function setServices($services)
	{
		$this->services = $services;
		
		return $this;
	}
	
	public function getService($name)
	{
		static $instances;
		
		if(isset($instances[$name]))
			return $instances[$name];
		
		if(!isset($this->services[$name]))
			throw new CException('Invalid service');
		
		$instance = Yii::createComponent($this->services[$name]);
		
		$instances[$name] = $instance;
		
		return $instance;		
	}
	public function getServices()
	{
		$r = array();
		
		foreach($this->services as $name => $service)
			$r[$name] = $this->getService ($name);
		
		return $r;
	}

	public function getPopup()
	{
		return $this->popup;
	}
	
	public function setPopup($popup)
	{
		$this->popup = $popup;
		
		return $this;
	}	
}
