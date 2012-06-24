<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	protected $_id;
	public $db_user = true;
	
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('email' => $this->username)); #email is stored in username variable
		
		$this->errorCode = self::ERROR_NONE;
		
		if($user === null && ($user = User::model()->findByAttributes(array('username' => $this->username))) === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
		{
		
			if($user->status != User::STATUS_ACTIVE)
			{
				Yii::app()->getUser()->setFlash('error', 'Account is not active.');
				return self::ERROR_NONE;
			}
			
			switch($user->hashing_method)
			{
				case 'plain':
					if($this->password != $user->web_password)
						$this->errorCode=self::ERROR_PASSWORD_INVALID;
					break;
				
				case 'sha256':
					if(strpos(Crypto::hash($this->password, $user->salt), $user->web_password) !== 0)
						$this->errorCode=self::ERROR_PASSWORD_INVALID;

					break;
						
				default:
					if(Crypto::oldhash($this->password) != $user->web_password)
						$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
		}
		
		#intialize user data
		if($this->errorCode == self::ERROR_NONE)
		{
			$this->setState('email', $user->email);
			$this->setState('username', $user->username);
			$this->setState('is_external', false);
						
			$this->_id = $user->id;
		}
				
		return !$this->errorCode;
	}
	
	public function apiAuth()
	{
		$this->authenticate();
		
		if($this->errorCode == self::ERROR_NONE)
		{
			return Crypto::hash($this->username.$this->email.$this->id, $this->email);
		}
		else
			return false;
	}

	public function apiCheckAuth($user, $key)
	{
		$user = User::model()->findByAttributes(array('email' => $this->username)); #email is stored in username variable
		
		$this->errorCode = self::ERROR_NONE;
		
		if($user === null && ($user = User::model()->findByAttributes(array('username' => $this->username))) === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
		{
			return Crypto::hash($user->username.$user->email.$user->id, $user->email) == $key;
		}	
		
		return false;
	}
	
	public function getId()
	{
		return $this->_id;
	}
}
