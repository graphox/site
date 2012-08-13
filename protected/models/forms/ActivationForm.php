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
			$model = Email::model()->findByAttributes(array('key' => $this->$field));

			if($model !== null)
			{
				if($model->status == Email::STATUS_BOTH)
					$model->status = Email::STATUS_PENDING;
					
				elseif($model->status == Email::STATUS_PENDING)
					$model->status = Email::STATUS_ACTIVE;
				
				
				$model->key = '**';
				$model->save(false);
				
				if($model->is_primary == 1)
				{
					$user = $model->user;
					if($user->status == User::STATUS_BOTH)
					{
						Yii::app()->user->setFlash('success', 'successfully activated acount, please wait for an admin to verify too.');
						$user->status = User::STATUS_ADMIN;
						$user->save(false);
						return;
					}
					elseif($user->status == User::STATUS_EMAIL)
					{
						Yii::app()->user->setFlash('success', 'successfully activated acount, you may now login.');
						$user->status = User::STATUS_ACTIVE;
						$user->save(false);
						return;
					}
				}
				
				Yii::app()->user->setFlash('success', 'successfully activated email.');				
				return;
			}
			
			$this->addError($field, 'invalid activation key.');
		}
	}
}
