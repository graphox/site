<?php

/**
 * An interface for objects that can have friends
 */
interface IFrienable
{
	/**
	 * Adds an object as friend to this entity
	 *
	 * @param int $objectGuid The entity id to add as friend
	 *
	 * @return bool success
	 */
	public function addFriend($objectGuid);

	/**
	 * Removes an object as a friend
	 *
	 * @param int $objectGuid The entity id to remove as friend
	 *
	 * @return bool success
	 */
	public function removeFriend($objectGuid);

	/**
	 * Determines whether or not the object is a friend of this entity
	 *
	 * @param int $objectGuid The entity id of the entity this entity may or may not be friends with
	 *
	 * @return bool is friend
	 */
	public function isFriend($objectGuid);

	/**
	 * Returns the friends of the entity
	 *
	 * @return array the friends
	 */
	public function getFriends();
}
