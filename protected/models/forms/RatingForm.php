<?php

class RatingForm extends CFormModel
{
	public $parentId;
	public $vote;
	
	public function rules()
	{
		return array(
			array('parentId, vote', 'required'),
			array('parentId, vote', 'numerical'),
			array('parentId', 'validateParent'),
			array('vote', 'validateVote')
		);
	}
	
	public function validateParent()
	{
		if(!$this->hasErrors() && Entity::model()->findByPk($this->parentId) === null)
			$this->addError('parentId', 'invalid parent.');
		elseif(!$this->hasErrors() && !$this->parent->typeModel->can('like'))
			$this->addError('parentId', 'You are not allowed to like this entity.');
		elseif(!$this->hasErrors() && $this->parent->findRelation('like', Yii::app()->user->entity->id) !== null)
			$this->addError('parentId', 'You have already liked this entity.');
			
	}

	public function validateVote()
	{
		$this->vote = $this->vote == '1'?1:0;
	}
	
	public function getParent()
	{
		static $parent;
		
		if(!isset($parent) || $parent->id !== $this->parentId)
			$parent = Entity::model ()->findByPk ($this->parentId);
		
		return $parent;
	}
	
	public function rate()
	{
		return $this->parent->addRelation('like', Yii::app()->user->entity->id, $this->vote);
	}
}