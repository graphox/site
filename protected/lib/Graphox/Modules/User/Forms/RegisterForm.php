<?php

namespace Graphox\Modules\User\Forms;

use \Yii;
use \CHtml;

class RegisterForm extends \CFormModel
{
	public $mailFrom = 'account@localhost';
	
	public $username;
	public $password;
	public $passwordRepeat;
	
	public $name;
	
	public $email;
 
	public function rules()
	{
		return array(
			array('username,password,name,email', 'required'),
			//array('password', 'equal', 'field' => 'passwordRepeat'),
			array('email', 'email')
		);
	}

	public function attributeLabels()
	{
		return array(
			'username'	=> Yii::t('user.register', 'Username'),
			'password'	=> Yii::t('user.register', 'Password'),
			'passwordRepeat'=> Yii::t('user.register', 'Repeat password'),
			'name'=> Yii::t('user.register', 'Name'),
			'email'=> Yii::t('user.register', 'Email'),
		);
	}
	
	public function getFormConfig()
	{
		return array(
			'title' => Yii::t('user.register', 'Register'),
			'showErrorSummary' => true,
			'action' => array( '/user/auth/register' ),
			'elements' => array(
				'username' => array(
					'type' => 'text',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.register', 'The username of your account'),
					'placeholder'	=> Yii::t('user.register', 'Your username'),
					'class' => 'input-large',
					'append' => '<i class="icon-user"></i>',
				),
				
				'<fieldset>',
				'<legend><div class="alert alert-info">Everything below can be changed later on.</div></legend>',
				
				'email' => array(
					'type' => 'text',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.register', 'The password of your account'),
					'placeholder'	=> Yii::t('user.register', 'Repeat your password'),
					'class' => 'input-large',
					'append' => '<i class="icon-envelope"></i>',
				),
				
				'password' => array(
					'type' => 'password',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.register', 'The password of your account'),
					'placeholder'	=> Yii::t('user.register', 'Your password'),
					'class' => 'input-large',
					'append' => '<i class="icon-exclamation-sign"></i>',
				),
				
				'passwordRepeat' => array(
					'type' => 'password',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.register', 'The password of your account'),
					'placeholder'	=> Yii::t('user.register', 'Repeat your password'),
					'class' => 'input-large',
					'append' => '<i class="icon-exclamation-sign"></i>',
				),
				
				'name' => array(
					'type' => 'text',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.register', 'Your full name'),
					'placeholder'	=> Yii::t('user.register', 'Your name'),
					'class' => 'input-large',
					'append' => '<i class="icon-user"></i>',
				),
				'</fieldset>'
			),
			
			 'buttons' => array(
				 'submit' => array(
					'type'			=> 'submit',
					'layoutType'	=> 'primary',
					'label'			=> Yii::t('user.register', 'Register'),
				)
			),
		);
	}
	
	private function getModule()
	{
		return Yii::app()->getModule('user');
	}
	
	public function save($validate = true)
	{
		if(!$validate || $this->validate())
		{
			$message = new \Graphox\Mail\Message(
				\Yii::t(
					'user.register',
					'{username} please activate your account',
					array(
						'{username}' => ucfirst($this->username)
					)
				)
			);
			
			$message->setFrom($this->mailFrom);
			$message->setTo(array($this->email => $this->username));

			$key = '';
			do
			{
				$key = md5(uniqid().$key);
			}
			while(Yii::app()->neo4j->getRepository('\Graphox\Modules\User\Email')->findOne(array('activationKey' => $key)));

			$message->setBody(
				Yii::app()->controller->renderInternal(
					dirname(__DIR__).'/views/mail/register.txt.php',
					array(
						'user' => $this,
						'activationKey' => $key
					),
					true
				)
			);
			//$message->addPart(Yii::app()->controller->renderInternal(dirname(__DIR__).'/views/mail/register.txt.php', array('user' => $this, 'activationKey' => $key), true), 'text/html');
			
			if(!Yii::app()->mailer->sendMessage($message))
			{
				Yii::log('Could not send activation email to: '.$this->email.' '.$key, 'error', 'as.user');
				$this->addError('username', 'Could not send activation email, please contact an admin');
				return false;
			}
			
			$email = new \Graphox\Modules\User\Email;
			$email->setActivationKey($key);
			$email->setEmail($this->email);
			$email->setIsPrimary(true);
			$email->setIsActivated(false);
			
			$user = new \Graphox\Modules\User\User;
			$user->addEmail($email);
			$user->setFullName($this->name);
			$user->setUsername($this->username);
			
			$user->setPassword(Yii::app()->crypto->encodePassword($this->password));
			
			Yii::app()->neo4j->persist($email);
			Yii::app()->neo4j->persist($user);
			Yii::app()->neo4j->flush();
					
			Yii::log('Registered account: '.$this->username.' key: '.$key, 'info', 'as.user');
			return true;
		}
		
		return false;
	}
}

