<?php

class PublicChannel extends Neo4JNode
{
	public function getEvents()
	{
		
	}
	
	public function traversals()
	{
		return array(
			'events' => array(self::HAS_MANY, self::NODE, '')
		)
	}
}

?>
