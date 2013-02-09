<?php

class UserStatus
{
	const BANNED = 0;
	const ACTIVE = 1;
	const NOT_ACTIVATED = 2;
	
	const OAUTH_ACCOUNT = 3;
	const INACTIVE = 3;
	
	static $names = array(
		0 => "banned",
		1 => "active",
		2 => "not_activated",
		3 => "oauth_account",
		4 => "inactive"
	);
	
	static function getName($id)
	{
		if(!isset(self::$names[$id]))
			return null;
		return self::$names[$id];
	}
	
	static function getId($name)
	{
		return array_search($name, self::$names); 
	}
}
