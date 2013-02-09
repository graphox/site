<?php

class Node extends Neo4jNode
{

	protected $eventsEnabled = true;
	public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
			'time'				=> array('type' => 'int'),
			'enableComments'	=> array('type' => 'bool'),
			'enableRatings'		=> array('type' => 'bool'),
			'lastComment'		=> array('type'	=> 'int'),
        ));
	}
	
	protected function afterValidate()
	{
		parent::afterValidate();
		$this->time = time();
	}
	
	public function afterSave()
	{
		parent::afterSave();
		
		if(!$this->isCreator())
		{
			$r = new _CREATOR_();
			$r->setStartNode(Yii::app()->user->id);
			$r->setEndNode($this);
			$r->save();
		}
	}
	
	public function getEvents()
	{
		return array(
			'update' => 'NodeUpdateEvent',
			'delete' => 'NodeDeleteEvent',
			'create' => 'NodeCreateEvent',
			
			'rate' => 'NodeRateEvent',
			'comment' => 'NodeCommentEvent',
		);
	}
	
	public function disableEvents()
	{
		$this->eventsEnabled = false;
	}
	
	public function enableEvents()
	{
		$this->eventsEnabled = true;
	}
	
	public function getStream()
	{
		$groupRelation = $this->getRelationships('_OWNER_');
		
		$relation = end($this->getRelationships('_CREATOR_'));
		
		if(count($groupRelation) != 0)
			$relation = new StreamList(array($relation, $groupRelation));
	
		return $relation;
	}
}
