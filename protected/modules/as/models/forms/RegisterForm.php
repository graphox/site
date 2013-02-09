<?php

class RegisterForm extends User
{
	public $retype_ingame_password;

	public $retype_web_password;
	public $web_password;
	
	public $status = 'pending';

	public function rules()
	{
		$rules = array(
			array('username, ingame_password, retype_ingame_password, email, web_password, retype_web_password', 'required'),
			
			array('username', 'length', 'max'=>10, 'min' => 3,
				'message' => Yii::t('as.user.register', "Incorrect username (length should be between 3 and 10 characters).")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
				'message' => Yii::t('as.user.register', "Incorrect symbols (A-z0-9).")),			
			array('username', 'unique',
				'message' => Yii::t('as.user.register', "This user's name already exists.")),
			
			array('web_password, ingame_password', 'length', 'max'=>128, 'min' => 4,
				'message' => Yii::t('as.user.register', "Incorrect password (minimum length is 4 characters).")),
			array('retype_web_password', 'compare', 'compareAttribute'=>'web_password',
				'message' => Yii::t('as.user.register', "Retype web Password is incorrect.")),
			array('retype_ingame_password', 'compare', 'compareAttribute'=>'ingame_password',
				'message' => Yii::t('as.user.register', "Retype ingame Password is incorrect.")),

			array('email', 'email'),
			array('email', 'unique',
				'message' => Yii::t('as.user.register', "This user's email address already exists.")),
		);
		
		/* Captcha
		if (!(isset($_POST['ajax']) && $_POST['ajax']==='registration-form'))
			array_push($rules, array(
				'verifyCode', 'captcha', 'allowEmpty'=>!UserModule::doCaptcha('registration')
			)
		);*/
		return $rules;
	}
	
	public function beforeSave()
	{
		$this->setHashedPassword($this->web_password);

		return parent::beforeSave();
	}
	
	public function afterSave()
	{
		#send activation email
		$key = new Activationkey;
		$key->user_id = $this->id;
				
		do
		{
			$key->hash = md5(uniqid());
		}
		while(Activationkey::model()->findByAttributes(array('hash' => $key->hash)));
				
		if($key->save(false))
		{
			$mailer = Yii::app()->mailer;
			
			$message = new AsEmail;
			$message->subject = Yii::t('as.user.register', 'Activate your account', array('{username}' => $this->username));
			$message->to = array($this->email => $this->username);

			$message->html_body = Yii::app()->controller->renderPartial('as.views.email.activate', array(
				'key' => $key->hash,
				'user' => $this
			), true);

			$message->body = Yii::app()->controller->renderPartial('as.views.email.activate_plain', array(
				'key' => $key->hash,
				'user' => $this
			), true);
			
			if(!$mailer->send($message))
			{
				Yii::log('Could not send activation email to: '.$this->email.' '.$this->key, 'error', 'as.user');
				$this->addError('username', 'Could not send activation email, please contact an admin');
				return false;
			}
		}
		else
		{
			Yii::log('Could not save activation key of: '.$this->email.' '.$this->key, 'error', 'as.user');
			$this->addError('username', 'Could not save activation key, please contact an admin');
		}
		
		Yii::log('Registered account: '.$this->username, 'info', 'as.user');
		
		return parent::beforeSave();
	}
}
