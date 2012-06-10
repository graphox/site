<?php

class RegisterForm extends User
{
	public $retype_ingame_password;
	public $retype_web_password;

	public function rules()
	{
		$rules = array(
			array('username, ingame_password, retype_ingame_password, email, web_password, retype_web_password', 'required'),
			
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => Yii::t('as::user::register', "Incorrect username (length between 3 and 20 characters).")),
			
			array('web_password', 'length', 'max'=>128, 'min' => 4,'message' => Yii::t('as::user::register', "Incorrect password (minimal length 4 symbols).")),
			array('ingame_password', 'length', 'max'=>128, 'min' => 4,'message' => Yii::t('as::user::register', "Incorrect password (minimal length 4 symbols).")),
			
			array('email', 'email'),
			
			array('username', 'unique', 'message' => Yii::t('as::user::register', "This user's name already exists.")),
			array('email', 'unique', 'message' => Yii::t('as::user::register', "This user's email address already exists.")),
			
			array('retype_web_password', 'compare', 'compareAttribute'=>'web_password', 'message' => Yii::t('as::user::register', "Retype web Password is incorrect.")),
			array('retype_ingame_password', 'compare', 'compareAttribute'=>'ingame_password', 'message' => Yii::t('as::user::register', "Retype Password is incorrect.")),
			
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => Yii::t('as::user::register', "Incorrect symbols (A-z0-9).")),
		);
		
		/* Captcha
		if (!(isset($_POST['ajax']) && $_POST['ajax']==='registration-form'))
			array_push($rules, array(
				'verifyCode', 'captcha', 'allowEmpty'=>!UserModule::doCaptcha('registration')
			)
		);*/
		return $rules;
	}
	
	public function save($runValidation=true, $attributes=null)
	{
		if(!$runValidation || $this->validate($attributes))
		{
			$this->salt = md5(uniqid());
			$this->hashing_method = 'sha256';
			
			$oldpassword = $this->web_password;
			$this->web_password = Crypto::hash($this->web_password, $this->hashing_method);
			$this->status = self::STATUS_NOT_ACTIVATED;
			
			$result = $this->getIsNewRecord() ? $this->insert($attributes) : $this->update($attributes);
			
			$this->web_password = $oldpassword;
			
			if($result)
			{
				$key = new ActivationKeys;
				$key->user_id = $this->id;
				
				do
				{
					$key->hash = md5(uniqid());
				}
				while(ActivationKeys::model()->findByAttributes(array('hash' => $key->hash)));
				
				if($key->save())
				{
					Yii::import('ext.swiftMailer.SwiftMailer');
					
					$settings = (object)Yii::app()->params['email.settings'];
					
					$content = Yii::app()->controller->renderPartial('as.views.email.activate', array(
						'key' => $key->hash,
						'user' => $this
					), true);

					$plain = Yii::app()->controller->renderPartial('as.views.email.activate_plain', array(
						'key' => $key->hash,
						'user' => $this
					), true);
					
					// Get mailer
					$SM = Yii::app()->swiftMailer;
					
					// New transport
					$transport = $SM->smtpTransport($settings->host, $settings->port);
					
					// Mailer
					$mailer = $SM->mailer($transport);
					
					// New message
					$message = $SM
						->newMessage('Activate your account')
						->setFrom(array($settings->from[1] => $settings->from[0]))
						->setTo(array($this->email => $this->username))
						->addPart($content, 'text/html')
						->setBody($plain);
					
					// Send mail
					if($mailer->send($message))
						return true;
				}
				
				return false; #TODO: undo registration
			}
			
			return $result;
		}
		else
			return false;
	}
	
	public function delay_save()
	{
		return array(
			'username' => $this->username,
			'ingame_password' => $this->ingame_password,
			'email' => $this->email,
			'web_password' => $this->web_password
		);
	}

	public function do_delay_save($in)
	{
		$this->username = $in['username'];
		$this->ingame_password = $in['ingame_password'];
		$this->email = $in['email'];
		$this->web_password = $in['web_password'];
		
		return $this->save(false);
	}
			
	public function getForm()
	{
		return new CForm(array(
			'showErrorSummary'=>true,
			'elements'=>array(
				
				'username'=>array(
					'hint'=>'6-12 characters; letters, numbers, and underscore'
				),
				
				'web_password'=>array(
					'type'=>'password',
					'hint'=>'8-12 characters; letters, numbers, and underscore; mixed case and at least 1 digit',
				),
				
				'retype_web_password'=>array(
					'type'=>'password',
					'hint'=>'Re-type your password',
				),
				
				'email'=>array(
					'hint'=>'Your e-mail address'
				),
				
				'ingame_password'=>array(
					'hint'=>'Your ingame password'
				),
				
				'retype_ingame_password'=>array(
					'hint'=>'Your ingame password'
				),
			),
			'buttons'=>array(
				'submit'=>array(
					'type' => 'submit',
					'label' => 'Register',
					'class' => 'button-submit'
				)
			)
		), $this);
	}
}
