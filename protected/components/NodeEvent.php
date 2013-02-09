<?php

class NodeEvent extends Neo4jNode
{
	protected $privacySettings = array(
		'public' => true,
		'friends' => true,
		'friendOfFiends' => true,
		'members' => true,
	);
			
	public function setPrivacySettings($settings)
	{
		foreach ($settings as $key => $value)
		{
			$this->privacySettings[$key] = $value;
		}
	}
	
	public function getWidget()
	{
		return 'application.components.wodgets.EventWidget';
	}
	
}

?>
