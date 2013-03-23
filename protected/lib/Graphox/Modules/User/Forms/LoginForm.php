<?php

namespace Graphox\Modules\User\Forms;

use \Yii;
use \CHtml;

class LoginForm extends \CFormModel
{
	/**
	 * @var User
	 */
	private $user;
	
	public $username;
	public $password;
	
	public $rememberMe;
 
	public function rules()
	{
		return array(
			array('username,password', 'required'),
			array('rememberMe', 'boolean'),
			
			array('password', 'validateAccount'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'username'	=> Yii::t('user.login', 'Username'),
			'password'	=> Yii::t('user.login', 'Password'),
			'rememberMe'=> Yii::t('user.login', 'Remember me'),
		);
	}
	
	public function getFormConfig($showRegister = true)
	{
		return array(
			'title' => 'Login',
			'showErrorSummary' => true,
			'action' => array( '/user/auth' ),
			'elements' => array(
				'username' => array(
					'type' => 'text',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.login', 'The username of your account'),
					'placeholder'	=> Yii::t('user.login', 'Your username'),
					'class' => 'input-large',
					'append' => '<i class="icon-user"></i>',
				),
				
				'password' => array(
					'type' => 'password',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.login', 'The password of your account'),
					'placeholder'	=> Yii::t('user.login', 'Your password'),
					'class' => 'input-large',
					'append' => '<i class="icon-exclamation-sign"></i>',
				),
				
				'rememberMe' => array(
					'type' => 'checkbox',
					//'hint' => Yii::t('user.login', 'Keep me logged in'),
				)
			),
			
			 'buttons' => array(
				 'submit' => array(
					'type'			=> 'submit',
					'layoutType'	=> 'primary',
					'label'			=> Yii::t('user.login', 'Log in'),
				),
				$showRegister ? CHtml::link(Yii::t('user.login', 'Register'), array('/user/auth/register'), array('class' => 'btn btn-link')) : ''
			),
		);
	}
	
		/**
	 * Validate the username/email - password combination
	 */
	public function validateAccount()
	{
		if($this->hasErrors())
			return;

		$userRepository = Yii::app()->neo4j->getRepository('\Graphox\Modules\User\User');
		/**
		 * @var \Graphox\Modules\User\User
		 */
		$user = $userRepository->findOneByUserName($this->username);
		
		if($user !== null && Yii::app()->crypto->checkPassword($this->password, $user->getPassword()))
		{
			if(!$user->isActivated())
			{
				$this->addError ('username', Yii::t('user.login', 'The user is not activated, please check your email for the activation key.'));
				return;
			}				
			
			$this->user = $user;
			return;
		}
		
		$this->addError('username', 'username/email or password incorrect.');
	}
	
	/**
	 * put the user in the session
	 */
	public function login()
	{
		$userIdentity = new \Graphox\Modules\User\UserIdentity($this->user);
		
		if($this->rememberMe === true)
			Yii::app()->user->login($userIdentity, $user->getRememberUserTime()); #7 days
			
		else
			Yii::app()->user->login($userIdentity);
		
		return true;
	}

}

