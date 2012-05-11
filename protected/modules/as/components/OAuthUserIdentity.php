<?php

class OAuthUserIdentity extends UserIdentity
{
	protected $_user;
	public function __construct($user)
	{
		$this->_id = $user->id;
		$this->_user = $user;

		$this->username = $this->_user->username;
	}
	
	public function authenticate()
	{
		$this->setState('email', $this->_user->email);
		$this->setState('username', $this->_user->username);
		$this->setState('is_external', true);
		

		
		return !self::ERROR_NONE;
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function getName()
	{
		return $this->username;
	}
}
