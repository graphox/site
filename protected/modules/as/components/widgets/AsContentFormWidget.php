<?php
class AsContentFormWidget extends AsWidget
{
	public $model;
	
    public function run()
    {
		if(!isset($this->model))
			throw new CException('data attribute missing!');
		
		$this->model->renderClass->renderForm($this->model);
    }
}
