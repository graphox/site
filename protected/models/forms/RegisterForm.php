<?php

class RegisterForm extends User
{
	public $retypePassword = '';
	


	public function rules()
	{
		$rules = array(
			array('email, username, password, retypePassword', 'required'),
			array('username', 'length', 'max'=>45),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
				'message' => Yii::t('as.user.register', "Incorrect symbols (A-z0-9).")),			
			array('retypePassword', 'compare', 'compareAttribute'=>'password',
				'message' => Yii::t('as.user.register', "Retype Password is incorrect.")),
			array('email', 'email'),
			
			array('email', 'ENeo4jValidatorUnique'),
			array('username', 'ENeo4jValidatorUnique'),
		);
		
		/* Captcha
		if (!(isset($_POST['ajax']) && $_POST['ajax']==='registration-form'))
			array_push($rules, array(
				'verifyCode', 'captcha', 'allowEmpty'=>!UserModule::doCaptcha('registration')
			)
		);*/
		return $rules;
	}
	
	public function setAttributes($values, $safeOnly = true, $checkPassword = false)
	{
		parent::setAttributes($values, $safeOnly, $checkPassword);
	}
	
	public function NeoUnique($field, $settings)
	{
		if(count(self::model()->findAllByAttributes(array($field =>$this->$field))) !== 0)
			$this->addError ($field, (string)$field.' already exists.');
	}
	
	public function afterValidate()
	{
		if(!$this->hasErrors())
			$this->encodePassword();
		
		parent::afterValidate();
	}
	
	public function afterSave()
	{
		static $done;
		
		if(isset($done))
			return parent::afterSave ();
		
		$done = true;
		
		return $this->actionRegistered();
		#send activation email
		$email = new Email;
		$email->user_id = $this->id;
		$email->email = $this->email;
		$email->status = Email::STATUS_BOTH;
		$email->is_primary = 1;
		$email->ip = $_SERVER["REMOTE_ADDR"];
		
				
		do
		{
			$email->key = md5(uniqid());
		}
		while(Email::model()->findByAttributes(array('key' => $email->key)));
				
		if($email->save(false))
		{
			$mailer = Yii::app()->mailer;
			
			$message = new AsEmailMessage;
			$message->subject = Yii::t('as.user.register', 'Activate your account', array('{username}' => $this->username));
			$message->to = array($this->email => $this->username);

			$message->html_body = Yii::app()->controller->renderPartial('//mail/activate', array(
				'key' => $email->key,
				'user' => $this
			), true);

			$message->body = Yii::app()->controller->renderPartial('//mail/activate_plain', array(
				'key' => $email->key,
				'user' => $this
			), true);
			
			if(!$mailer->send($message))
			{
				Yii::log('Could not send activation email to: '.$this->email.' '.$email->key, 'error', 'as.user');
				$this->addError('username', 'Could not send activation email, please contact an admin');
				return false;
			}
		}
		else
		{
			Yii::log('Could not save activation key of: '.$this->email.' '.$email->key, 'error', 'as.user');
			$this->addError('username', 'Could not save activation key, please contact an admin');
		}
		
		Yii::log('Registered account: '.$this->username, 'info', 'as.user');
		
		return parent::beforeSave();
	}
}
