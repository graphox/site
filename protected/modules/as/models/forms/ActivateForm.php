<?php

class ActivateForm extends CFormModel
{
	public $activation_key;

	public function rules()
	{
		$rules = array(
			array('activation_key', 'required'),
			array('activation_key', 'length', 'max'=>500, 'message' => Yii::t('as::user::activate', "Incorrect activation key.")),
			array('activation_key', 'is_in_db'),
			
		);
		
		/* Captcha
		if (!(isset($_POST['ajax']) && $_POST['ajax']==='registration-form'))
			array_push($rules, array(
				'verifyCode', 'captcha', 'allowEmpty'=>!UserModule::doCaptcha('registration')
			)
		);*/
		return $rules;
	}
	
	public function setAttributes($value)
	{
		isset($value['activation_key']) && $this->activation_key = $value['activation_key'];
	}
	
	public function is_in_db($attribute, $params)
	{
		if(ActivationKeys::model()->findByAttributes(array('hash' => $this->$attribute)) === null)
			$this->addError($attribute, 'Invalid activation key ('.$this->$attribute.')');
	}
	
	public function save($runValidation=true, $attributes=null)
	{
		if(/*!$runValidation ||*/ $this->validate($attributes))
		{
			$key = ActivationKeys::model()->findByAttributes(array('hash' => $this->activation_key));
			
			$user = $key->user;
			$user->status = User::STATUS_ACTIVE;
			$user->save();
			
			$key->delete();
			
			return true;
		}
		else
			return false;
	}
	
	public function delay_save()
	{
		return array(
			'activation_key' => $this->activation_key
		);
	}

	public function do_delay_save($in)
	{
		$this->activation_key = $in['activation_key'];

		return $this->save(false);
	}
			
	public function getForm()
	{
		return new CForm(array(
			'showErrorSummary'=>true,
			'elements'=>array(
				'activation_key'=>array(
					'hint' => 'The activation key tou received by mail.'
				),
			),
			
			'buttons'=>array(
				'submit'=>array(
					'type' => 'submit',
					'label' => 'Next',
					'class' => 'button-submit'
				)
			)
		), $this);
	}
}
