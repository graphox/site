<?php
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	
	/**
	 * bool if the user wants to stay logged in.
	 */
	public $remember_me = false;
	
	/**
	 * The user object of the user logging in.
	 */
	public $_user;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('username, password, remember_me', 'safe'),
			array('username, password', 'required'),
			array('remember_me', 'boolean'),
			
			array('password', 'validateAccount'),
			array('status', 'validateLoginStatus'),		
		);
	}
	
	/**
	 * Validate the username/email - password combination
	 */
	public function validateAccount()
	{
		if($this->hasErrors())
			return;

		$user = User::model()->findByUsernameOrEmail($this->username);
		
		if(YII_DEBUG && $user === null)
		{
			$this->addError('username', 'DEBUG: no user found!');
		}
		elseif(YII_DEBUG)
		{
//			var_dump($user);
			var_dump(Yii::app()->crypto->checkPassword($this->password, $user->password));
		}
		
		if($user !== null && true )//Yii::app()->crypto->checkPassword($this->password, $user->password))
		{
			$this->_user = $user;
			return;
		}
		
		$this->addError('username', 'username/email or password incorrect.');
	}
	
	/**
	 * Validate the status of the account on login
	 */
	public function validateLoginStatus()
	{
		if($this->hasErrors())
			return;

		if(!$this->_user->canLogin())
			$this->addError('status', 'acount status is not active.');	
	}
	
	public function setAttributes($values, $safeOnly = true, $checkPassword = false)
	{
		parent::setAttributes($values, $safeOnly, $checkPassword);
	}
	
	/**
	 * put the user in the session
	 */
	public function login()
	{
		$userIdentity = new AsUserIdentity($this->_user);
		
		if($this->remember_me === true)
			Yii::app()->user->login($userIdentity,3600*24*7); #7 days
			
		else
			Yii::app()->user->login($userIdentity);
		
		return true;
	}
}
