<?php


class Access
{
	public $read;
	public $write;
	public $update;
	public $delete;
	
	public static function FromResult($result, $classname = __CLASS__)
	{
		$access = new $classname;
		$access->read = $result->read;
		$access->write = $result->write;
		$access->update = $result->update;
		$access->delete = $result->delete;
		
		return $access;
	}
}

class AccessControl
{
	static $cache = array();
	public static function init()
	{
	
	}
	
	public static function GetObjectByName($name)
	{
		return AclObject::model()->findByAttributes(array('name' => $name));
	}

	public static function AddObject($name, $parent = null)
	{
		$acl = new AclObject;
		
		if($parent === null || is_string($parent))
		{
			if($parent === null)
				$root = self::GetObjectByName('site'); #TODO: make setting for root object
			else
				$root = self::GetObjectByName($parent);
			
			if($root === null)
				throw new exception('Could not add object with empty parent, could not find main root');
			
			$acl->parent_id = $root->id;
		}
		elseif(is_object($parent))
			$acl->parent_id = $parent->id;
		else
			$acl->parent_id = (int)$parent;
		
		$acl->name = $name;
		return $acl->save();
	}

	static function GetUserAccess($object, $user = false)
	{
		if(!is_object($object))
			throw new exception('Could not find object');
			
		if($user === false)
		{
			$user = Yii::app()->user->id;
		}
			
		if(!is_object($user))
		{
			$user = User::Model()->with('aclGroupUsers')->findByAttributes(array('id' => $user));
			
			if($user === null)
				throw new exception('Access not found, Could not find user from id');
		}

				
		$groups = $user->aclGroups;
		$privileges = $object->aclPrivileges;
		
		#If we can't find permissions for this object, try the parent
		while($privileges === null && $object !== null)
		{
			$object = $object->parent;
			$privileges = $object->aclPrivileges;
		}
		
		if($object === null)
			throw new exception('Could not find object priviliges');

		
		#TODO: let the database sort
		usort($privileges, function($a, $b)
		{
			if($a->order == $b->order)
				return 0;
			elseif($a->order < $b->order)
				return 1;
			else
				return -1;
		});
		
		$found = false;
		do
		{
			#try all privileges on all groups
			foreach($privileges as $privilege)
			{
				foreach($groups as $group)
				{
					if($group->id != $privilege->group_id)
						continue;
					else
					{
						$found = true;
						$access = Access::FromResult($privilege);
						break 3;
					}
				}
			}
			
			
			#try parents
			foreach($groups as $i => $group)
			{
				$groups[$i] = $group->parent;
					
				if($groups[$i] === null)
					unset ($groups[$i]);
			}
		}
		while($found === false && count($groups) !== 0);
		
		if(!$found)
			throw new exception('Could not find rule');
		
		return $access;
	}
	
	static function GetAccess($name, $is_page = true)
	{
		if($is_page)
			$name = 'Page:'.$name;
		
		$object = self::GetObjectByName($name);
		
		if(!$object)
			return false;
		
		return self::GetUserAccess($object);
	}
}

AccessControl::init();
