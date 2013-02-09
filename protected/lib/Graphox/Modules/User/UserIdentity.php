<?php

class UserIdentity extends CUserIdentity
{
	protected $_id;
	protected $_isAdmin;
	
	/**
	 * 
	 * @param User $user
	 */
	public function __construct($user)
	{
		$this->_id = $user->id;
		$this->_isAdmin = $user->isAdmin();
		$this->setState('isAdmin', $user->isAdmin());
		$this->setState('username', $user->username);
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function getIsAdmin()
	{
		return $this->_isAdmin;
	}
	
	public function isAdmin()
	{
		return $this->isAdmin;
	}
}
