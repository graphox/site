<?php

class AsUserIdentity extends CUserIdentity
{
	protected $_user;
	
	public function __construct($user)
	{
		$this->_user = $user;
	}
	
	public function getId()
	{
		return $this->_user->id;
	}
	
	public function getUser()
	{
		return $this->_user;
	}
}
