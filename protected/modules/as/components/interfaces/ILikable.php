<?php

/**
 * An interface for objects that can be liked
 */
interface ILikable
{
	/**
	 * Adds a like to this entity
	 *
	 * @param int $objectGuid The entity id wich performs the like
	 *
	 * @return bool success
	 */
	public function addLike($objectGuid);

	/**
	 * Removes a Like
	 *
	 * @param int $objectGuid The entity id wich unperforms the like
	 *
	 * @return bool success
	 */
	public function removeLike($objectGuid);

	/**
	 * Determines whether or not the object has liked this entity
	 *
	 * @param int $objectGuid The entity id of the entity this entity may or may not has liked
	 *
	 * @return bool is friend
	 */
	public function hasLiked($objectGuid);

	/**
	 * Returns the entities that liked this entity
	 *
	 * @return array the entities which have liked this entity
	 */
	public function getLikes();
}
