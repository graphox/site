<?php

/**
 * An interface for objects that can be joined
 */
interface IJoinable
{
	/**
	 * Adds an object as member to this entity
	 *
	 * @param int $objectGuid The entity id to add as member
	 *
	 * @return bool success
	 */
	public function addMember($objectGuid);

	/**
	 * Removes an object as a member
	 *
	 * @param int $objectGuid The entity id to remove as member
	 *
	 * @return bool success
	 */
	public function removeMember($objectGuid);

	/**
	 * Determines whether or not the object is a member of this entity
	 *
	 * @param int $objectGuid The entity id of the entity this entity may or may not be member with
	 *
	 * @return bool is member
	 */
	public function isMember($objectGuid);

	/**
	 * Returns the members of the entity
	 *
	 * @return array the memberss
	 */
	public function getMembers();
}
