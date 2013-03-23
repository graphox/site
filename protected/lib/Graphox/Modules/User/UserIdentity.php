<?php

namespace Graphox\Modules\User;

class UserIdentity extends \CUserIdentity
{
	protected $_id;
	protected $_isAdmin;
	
	/**
	 * 
	 * @param User $user
	 */
	public function __construct($user)
	{
		$this->_id = $user->getId();
		$this->_isAdmin = $user->isAdmin();
		$this->setState('isAdmin', $user->isAdmin());
		$this->setState('username', $user->getUsername());
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
