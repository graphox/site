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
	
	public static function create($read = true, $write = false, $update = false, $delete = false, $classname = __CLASS__)
	{
		$access = new $classname;
		$access->read = $read;
		$access->write = $write;
		$access->update = $update;
		$access->delete = $delete;
		
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

	public static function AddObject($name, $parent = null, $default_access = null)
	{
		$acl = new AclObject;
		
		if($default_access === null)
			$default_access = Access::create(true, true, false, false);

		
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
		if(!$acl->save())
			throw new Exception('Could not save privilege! '.print_r($acl->getErrors(), true));
		
		if($default_access !== -1)
			self::giveAccess($acl, null, $default_access);
		
		return $acl;
	}

	static function GetUserAccess($object, $user = false)
	{
		if(!is_object($object))
			throw new exception('Could not find object');
		
		if(!Yii::app()->user->isGuest)
		{
			
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
			$user = self::getGroup('user');
			
			if(!$user)
				throw new exception('DB corruption, group user not found!');
			
			$groups[] = $user;
		}
		
		$world = self::getGroup('world');
		
		if(!$world)
			throw new exception('DB corruption, group world not found!');
		
		$groups[] = $world;

		$fetch_parents = function($groups, &$parents, &$fetch_parents)
		{
			foreach($groups as $group)
			{
				$parents[$group->id] = $group->id;
				if($group->parent)
					$fetch_parents(array($group->parent), $parents, $fetch_parents);
			}
		};	
		
		#Fetch all groups that the user has joined, including all parent ones
		$fetch_parents($groups, &$group_ids, $fetch_parents);
		
		$fetch_objects = function($objects, &$parents, &$fetch_objects)
		{
			foreach($objects as $object)
			{
				$parents[$object->id] = $object->id;
				if($object->parent)
					$fetch_objects(array($object->parent), $parents, $fetch_objects);
			}
		};	
		#Fetch all the parent objects
		$fetch_objects(array($object), $object_ids, $fetch_objects);
		
				
		$privileges = AclPrivilege::model()->findAll(array('order' => 'order_by'));
		
		#try all possibillities based on the order
		foreach($privileges as $priv)
		{
			if(!isset($group_ids[$priv->group_id]))
				continue;
			
			if(!isset($object_ids[$priv->object_id]))
				continue;
		
			return Access::FromResult($priv);
		}	
				
		throw new exception('Could not find rule checked groups:'.print_r($group_ids, true).' Checked objects:'.print_r($object_ids, true));
	}
	
	static function GetAccess($name)
	{
		$object = self::GetObjectByName($name);

		return self::GetUserAccess($object);
	}
	
	#for lazy people:
	static public function getGroup($name)
	{
		if(is_string($name))
			return AclGroup::model()->findByAttributes(array('name' => $name));
		else
			return AclGroup::model()->findByPk((int)$name);
	}
	
	static public function addGroup($name, $parent_id = null)
	{
		if($parent_id === null)
		{
			$world = self::getGroup('world');
		
			if(!$world)
				throw new exception('Main group, "world" not found, please install/reinstall the Database');
			
			$parent_id = $world->id;
		}
		
		$group = new AclGroup;
		$group->parent_id = $parent_id;
		$group->name = $name;
		if(!$group->save())
			throw new Exception('Could not save group! '.print_r($group->getErrors(), true));
		
		return $group;
	}
	
	static public function giveAccess($object, $group = null, $access = null, $order = null)
	{
		if($group === null)
		{
			$group = self::getGroup('world');
		}
		elseif(is_string($group))
		{
			$group = self::getGroup($name);
			
			if(!$group)
				$group = self::addGroup($name);
		}
		elseif(!is_object($group))
		{
			#Create dummy object
			$group = new AclGroup;
			$group->id = $group;
		}
		
		$privs = AclPrivilege::model()->findByAttributes(array('group_id' => $group->id, 'object_id' => $object->id));
		
		#already 1 in the database
		if(count($privs) > 0)
			foreach($privs as $priv)
				return $priv;
		
		if($order === null)
		{
			$criteria = new CDbCriteria;
			$criteria->select='MAX(order_by) as max_order';
			$privilege = AclPrivilege::model()->find($criteria);
			$order = $privilege->max_order + 2;
		}
		else
			$order = (int) $order;
			
		
		if($access === null)
		{
			#find indirect parent of the new privilege
			$command = Yii::app()->db->createCommand('
				SELECT
					acl_privilege.id,
					acl_privilege.read,
					acl_privilege.write,
					acl_privilege.update,
					acl_privilege.delete,
					acl_privilege.order_by
				FROM
					acl_privilege,
					acl_object,
					acl_object as curr_object,
					acl_group,
					acl_group as curr_group
				WHERE
				(
					(
						acl_object.id = curr_object.parent_id
						AND
						curr_object.id = :object_id
					)
				OR
					(
						curr_group.parent_id = acl_group.id
						AND
						curr_group.id = :group_id
					)
				)
				ORDER BY
					acl_privilege.order_by');
			
			#workaround
			$obj_id = (int)$object->id;
			$g_id = (int)$group->id;
			
			#bin variables
			$command->bindParam(":object_id", $obj_id, PDO::PARAM_INT);
			$command->bindParam(":group_id", $g_id, PDO::PARAM_INT);
			
			$result = $command->query() ;
			
			#no indirect parents found
			if($result->getRowCount() === 0)
				$access = Access::create();
				
			#get highest ranked result
			else
			{
				$row = $result->read();
				if($row === false)
					throw new exception('something went really wrong');
				
				$row = (object)$row;

				$access = Access::create($row->read, $row->write, $row->update, $row->delete);
			}
			
		}
		
		$privilege = new AclPrivilege();
		$privilege->object_id = $object->id;
		$privilege->group_id = $group->id;
		
		$privilege->read = (int)$access->read;
		$privilege->write = (int)$access->write;
		$privilege->update = (int)$access->update;
		$privilege->delete = (int)$access->delete;
		
		$privilege->order_by = $order;
		
		if(!$privilege->save())
			throw new Exception('Could not save privilege! '.print_r($privilege->getErrors(), true));
		
		return $privilege;
	}
	
	public function AddGroupToUser($user, $group = null)
	{
		if($group === null)
		{
			$group = self::getGroup('world');
		}
		elseif(is_string($group))
		{
			$group = self::getGroup($name);
			
			if(!$group)
				$group = self::addGroup($name);
		}
		elseif(!is_object($group))
			throw new exception('invalid group');
		
		$group_user = new AclGroupUser;
		$group_user->group_id = $group->id;
		$group_user->user_id = $user->id;
		if(!$group_user->save())
			throw new Exception('Could not save privilege! '.print_r($privilege->getErrors(), true));
	}
}
