<?php

namespace Graphox\Modules\Admin;

class AdminModule extends \CWebModule
{
	public $controllerNamespace = '\Graphox\Modules\Admin\Controllers';
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{


			return true;
		}
		else
			return false;
	}
}
