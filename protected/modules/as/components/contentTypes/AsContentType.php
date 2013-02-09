<?php

abstract class AsContentType extends Content
{
	protected $view = 'as.views.contentType.base.view';
	protected $formView = 'as.views.contentType.base.form';
	protected $indexView = 'as.views.contentType.base.index';	
	
	public function render($can, $return = false)
	{
		return $this->renderView($this->view, array('model' => $this, 'can' => $can), $return);
	}
	
	public function renderForm($model, $return = false, $additional = array())
	{
		return $this->renderView($this->formView, array_merge($additional, array('model' => $model)), $return);
	}
	
	public function renderIndex($models, $return = false)
	{
		return $this->renderView($this->indexView, array('models' => $models), $return);
	}
		
	public function renderView($view, $data = array(), $return = false)
	{
		$data = array_merge(array(
			'this' => Yii::app()->controller
		), $data);
		
		Yii::app()->controller->renderPartial($view, $data, $return);
	}
}
