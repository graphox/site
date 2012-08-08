<?php
class LoginForm extends User
{
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
		
		$criteria=new CDbCriteria;
		$criteria->compare('emails.email', $this->username);
		$criteria->compare('emails.status', Email::STATUS_ACTIVE);
		$criteria->compare('username', $this->username, false, 'OR');
		$criteria->with = array('emails');
		$criteria->together = true;

		$user = self::model()->find($criteria);

		if($user !== null && Yii::app()->crypto->checkUserPassword($user, $this))
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
		
		if($this->_user->status !== self::STATUS_ACTIVE)
			$this->addError('status', 'acount status is not active.');	
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
