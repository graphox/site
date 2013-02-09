<?php

/**
 * An interface for objects wich access can be restricted to other objects
 */
interface IRestrictable
{
	/**
	 * get the available actions that can be performed on this entity
	 *  internally this will look throug plugins and the entity type for available actions
	 *
	 * @return array actions that can be called
	 */
	public function getActions();

	/**
	 * get an array with the actions the object is allowed to perform on this object
	 *
	 * @return array actions that can be performed
	 */
	public function getAccess($objectGuid);
}
