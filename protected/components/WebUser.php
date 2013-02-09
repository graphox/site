<?php

class WebUser extends CWebUser
{
	/**
	 * @return string the ip of the user browsing the page.
	 */
	public function getIp()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
	
	/**
	 * @return string the hostname of the user browsing the page.
	 */
	public function getHostName()
	{
		return gethostbyname($this->ip);
	}
	
	public function getIsAdmin()
	{
		return $this->hasState('isAdmin') && $this->getState('isAdmin');
	}
	
	public function getUsername()
	{
		return $this->hasState('username') ? $this->getState('username') : 'unnamed';
	}
}
