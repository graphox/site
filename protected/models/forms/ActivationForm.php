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
				{
					$model->status = Email::STATUS_PENDING;
					$model->save(false);
					if($model->is_primary === 1)
					{
						if($model->user->status == User::STATUS_BOTH)
						{
							$model->user->status = User::STATUS_ADMIN;
							$model->user->save(false);
						}
						elseif($model->user->status == User::STATUS_EMAIL)
						{
							$model->user->status = User::STATUS_ACTIVE;
							$model->user->save(false);
						}
					}
					return;
				}
				elseif($model->status == Email::STATUS_PENDING)
				{
					$model->status = Email::STATUS_ACTIVE;
					$model->save(false);
					if($model->user->status == User::STATUS_BOTH)
					{
						$model->user->status = User::STATUS_ADMIN;
						$model->user->save(false);
					}
					elseif($model->user->status == User::STATUS_EMAIL)
					{
						$model->user->status = User::STATUS_ACTIVE;
						$model->user->save(false);
					}
					return;
				}
			}
			
			$this->addError($field, 'invalid activation key.');
		}
	}
}
