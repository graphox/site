<?php

namespace Graphox\Modules\User\Forms;

use \Yii;
use \CHtml;

class RegisterForm extends \CFormModel
{
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
			'title' => 'Login',
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
}

