<?php

class AsController extends CController
{
	public $menu = array();
	public $breadcrumbs = array();

	public $layout = '//layouts/column2';
	
	public $title = '';

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
		if(!Yii::app()->user->isGuest)
			throw new CHttpException(403, 'Access denied');
		
		Yii::app()->user->setReturnUrl(Yii::app()->request->url);
		
		$this->redirect(Yii::app()->settings->get('as.loginUri', array('//as/auth')));
	}
	
	public function render($view, $data=NULL, $return=false)
	{
		if(isset($_POST['noLayout']))
			return $this->renderPartial($view, $data, $return);
		else
			return parent::render($view, $data, $return);
	}
	
	protected function performAjaxValidation($model, $id)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === $id)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
