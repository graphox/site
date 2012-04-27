<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('email' => $this->username)); #email is stored in username variable
		$crypto = new Crypto;
		
		$this->errorCode = self::ERROR_NONE;
		
		if($user === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
		{
			$web_user = $user->web_user;
			
			if($web_user === null)
				$this->errorCode=self::ERROR_USERNAME_INVALID; #this user doesn't have a website account
			elseif(isset($web_user->hash_method))
				switch($web_user->hash_method)
				{
					case 'plain':
						if($this->password != $web_user->pass)
							$this->errorCode=self::ERROR_PASSWORD_INVALID;
						break;
						
					default:
						if($crypto->oldhash($this->password) != $web_user->pass)
							$this->errorCode=self::ERROR_PASSWORD_INVALID;
				}
			elseif($crypto->oldhash($this->password) != $web_user->pass)
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		
		#intialize user data
		if($this->errorCode == self::ERROR_NONE)
		{
			$this->setState('email', $user->email);
			$this->setState('username', $user->name);
			
			$this->_id = $user->id;
		}
				
		return !$this->errorCode;
	}
	
	public function getId()
	{
		return $this->_id;
	}
}
