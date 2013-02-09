<?php

/**
	@class AsAccessControl
	Handle the access control
*/

class AsAccessControl extends CComponent
{
	public function init()
	{
	
	}

	/**
		@function getObjectByName
		@attribute name the name of the object
		Wrapper around the model for easy searching
	*/
	public function getObjectByName($name)
	{
		return AclObject::model()->findByAttributes(array(
			'name' => $name
		));
	}
	
	/**
		@function getObject
		@attribute id the id of the object
		Wrapper around the model for easy selecting
	*/
	public function getObject($id)
	{
		return AclObject::model()->findByPk($id);
	}
	
	/**
		@function findObject
		@attribute the name or the id of the object
		@attribute create = false create the object if not found
		searches for an object in id and name column
	*/
	
	protected function findObject($find, $create = false)
	{
		if(is_string($find))
			$object = $this->getObjectByName($find);
		else
			return $this->getObject($find);
		
		if($object === null && $create !== false)
			$this->addObject($find, $create);
		
		return $object;
	}
	
	/**
		@function addObject
		@attribute name the name of the object
		@attribute default = 0 the default attribute
		adds an object
	*/
	public function addObject($name)
	{
		while(
			null !== AclObject::model()->findByAttributes(array
			(
				'name' => $name
			))
		)
			$name = $name.'_';
		
		$model = new AclObject;
		$model->name = $name;

		if(!$model->save(false))
			throw new CException('could not save object');
		
		return $model;
	}
	
	/**
		@function addGroup
		@attribute name
		adds a group to the database
	*/
	public function addGroup($name)
	{
		$object = $this->addObject('groups.'.$name);
		
		$model = new Group;
		$model->name = $name;
		$model->acl_object_id = $object->id;

		if(!$model->save(false))
			throw new CException('Could not save group');
		
		return $model;
	}
	
	/**
		@function getGroupByName
		@attribute name name of the group
		@attribute create = false Create the group when not found
		Wrapper around the model for easy searching
	*/
	public function getGroupByName($name, $create = false)
	{
		$group = Group::model()->findByAttributes(array(
			'name' => $name
		));
		
		if($group === null && $create)
			$group = $this->addGroup($name);
		
		return $group;
	}
	
	/**
		@function getAccess
		@attribute object
		@attribute actions
		get access rules for the current user

		@link getUserAccess
	*/
	public function getAccess($object, $actions)
	{
		if(Yii::app()->user->isGuest)
			$user = (object)array(
				'groups' => array(
					$this->getGroupByName('anonymous', true),
					$this->getGroupByName('world', true),
				)
			);
		else
			$user = User::model()->with('groups')->findByPk(
				Yii::app()->user->id
			);
	
		return $this->getUserAccess($user, $object, $actions);
	}
	
	/**
		@function getUserAccess
		@attribute user the user object
		@attribute object the acl object
		@attribute actions an array containing all actions in name => default pairs
		get access rules for a specific user
	*/
	public function getUserAccess($user, $object, $actions, $default = 0)
	{
		if(!is_object($object))
			$object = $this->findObject($object, $default);
	
		$groups = $user->groups;
		$access = array();
		
		foreach($actions as $action	=> $defaultValue)
		{
			$objectAction = AclAction::model()->findOrCreate(array(
				'acl_object_id' => $object->id,
				'name' => $action
			), array('default_value' => (int)$defaultValue));
			
			foreach($groups as $group)
			{
				$groupActions = GroupAction::model()->findAllByAttributes(array(
					'group_id' => $group->id,
					'action_id' => $objectAction->id
				), array('order' => 'order_id'));
				
				foreach($groupActions as $groupAction)
				{
					$access[$action] = (bool)$groupAction->value;
					break 2;
				}
				
			}
			
			if(!isset($access[$action]))
				$access[$action] = (bool)$objectAction->default_value;
		}
		
		return (object)$access;
	}
}
