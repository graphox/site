<?php

class AsUserIdentity extends CUserIdentity
{
	protected $_id;
	
	public function __construct($user)
	{
		$this->_id = $user->id;
	}
	
	public function getId()
	{
		return $this->_id;
	}
}
