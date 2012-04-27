<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	public $db_user = true;
	
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('email' => $this->username)); #email is stored in username variable
		$crypto = new Crypto;
		
		$this->errorCode = self::ERROR_NONE;
		
		if($user === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
		{
			switch($user->hashing_method)
			{
				case 'plain':
					if($this->password != $user->web_password)
						$this->errorCode=self::ERROR_PASSWORD_INVALID;
					break;
						
				default:
					if($crypto->oldhash($this->password) != $user->web_password)
						$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
		}
		
		#intialize user data
		if($this->errorCode == self::ERROR_NONE)
		{
			$this->setState('email', $user->email);
			$this->setState('username', $user->username);
			
			$this->_id = $user->id;
		}
				
		return !$this->errorCode;
	}
	
	public function getId()
	{
		return $this->_id;
	}
}
