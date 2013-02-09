<?php

class StreamList
{
	private $list;
	public function __construct($list)
	{
		$this->list = $list;
	}
	
	public function __call($function, $args)
	{
		foreach($list as $row)
		{
			call_user_func_array(array($row, $function), $args);
		}
	}
}

?>
