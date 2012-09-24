<?php

class ActivationForm extends CFormModel
{
	public $activation_key;
	
	public function init()
	{
		if(isset($_GET['key']))
			$this->activation_key = $_GET['key'];
		
		parent::init();
	}
	
	public function rules()
	{
		return array(
			array('activation_key', 'required'),
			array('activation_key', 'length', 'min' => 3),
			array('activation_key', 'validActivation'),
		);
	}
	
	public function validActivation($field)
	{
		if(!$this->hasErrors())
		{
			$model = User::model()->findByAttributes(array(
				// Fixes bug in Neo4Yii
				'emailActivationKey' => str_replace('$', '\\$', $this->$field)
			));
			
			if($model !== null && $model->actionEmailActivated())
			{
				return;
			}
			
			$this->addError($field, 'invalid activation key.');
		}
	}
}
