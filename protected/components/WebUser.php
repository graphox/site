<?php

class WebUser extends CWebUser
{
	public function getNode()
	{
		static $node;
		
		if(!isset($node))
		{
			$node = User::model()->findById($this->id);
		}
		
		if($node->id !== $this->id)
		{
			unset ($node);
			return $this->getNode();
		}
		
		return $node;
	}
	
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
}
