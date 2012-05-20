<?php

class AsController extends CController
{
	public $menu;
	public $breadcrumbs;

	public 	$layout = '//layouts/main';

	public function __construct($id,$module=null)
	{
		parent::__construct($id,$module);
		
		/*
		// set elements on panel "adminPanel"
		Yii::app()->getPanel('adminPanel')
				->separator() // add separator
				->raw('Hello <strong>'.Yii::app()->user->name.'</strong>.<br />How are you?') // add raw text
				->separator() // add separator
				->single(
				    'Big button', // label
				    array('controller/action', 'id' => 1), // url
				    'http://goo.gl/AMVKB' // icon
				) // add single element
				->stack(array(
				    array('label' => 'show', 'url' => '#', 'icon' => 'http://goo.gl/Wkvb3'),
				    array('label' => 'more', 'url' => '#', 'icon' => 'http://goo.gl/6F5Ar'),
				)) // add stack elements
				->separator(); // add separator
		
		// set elements on bar "adminPanel"
		Yii::app()->getPanel('adminPanel')
				->pushOnBar(QtzPanel::BAR_DB) // add DB stats
				->pushOnBar(QtzPanel::BAR_LOGS) // add logs
				->pushOnBar(QtzPanel::BAR_MEMORY) // add memory stats
				->pushOnBar(QtzPanel::BAR_EXECUTION_TIME) // add time info
				->pushOnBar('custom info'); // add custom info
		*/
	}
	
	public function denyAccess($msg = null)
	{
		Yii::import('as.models.forms.*');
		$model = new LoginForm;

		$this->layout = '//layouts/main';
		Yii::app()->user->setReturnUrl(Yii::app()->request->url);
		$this->render('as.views.auth.login',array('model'=>$model));
		Yii::app()->end();
	}
	
	public function addMenu($name, $elements)
	{
		$this->menu[] = array($name, $elements);
	}
}
