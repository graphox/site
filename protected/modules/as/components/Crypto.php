<?php

class Crypto
{
	static $key = 'AkeyWICHyouSHOULDchange!';
	static $salt = 'AsalyWICHyouMIGHTwantTOchange';
	static $peper = 'ApeperWICHyouMIGHTwantTOchange';
	
	function hash($input, $pepper = null)
	{
		if($pepper === null)
			$pepper = static::$peper;
			
		if(	ord($input[floor( strleng($input) /2 )]) < ord($input[floor( strleng($input) /4	)]) )
			$input = static::$salt.$input;
		else
			$input .= static::$salt;
		
		return hash_hmac ('sha256', $input, static::$key);
	}
	
	public function old_hash($code = false)
	{
		if (!$code) return;
		#thanks to http://www.phphulp.nl/ for the idea
		
		$array = str_split($code); #string => array
		$string = '';
		foreach($array as $row)
			$string .= sha1($code);
		
		$string = md5($string);
		return $string;
	}
}
