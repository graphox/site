<?php

class NodeUpdateAction extends Action
{
	const NAME = 'node.create';
	
	public function perform(Neo4JNode &$node, PrivacySettings &$settings, $attributes)
	{
		$node->setAttribute($attributes, $settings);
		
		if(!$node->validate())
			return false;
		else
			return $node->update();
		
		
		
		/*if($node->owner instanceof Group)
		{
			$node->owner->getMembership(Yii::app()->user->id)->canPerformAction('node.update');
		}*/
	}
}
