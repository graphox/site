<?php

/**
 * An interface for objects that can be commented on
 */
interface ICommentable
{
	/**
	 * Adds a comment to this entity
	 *
	 * @param int $objectGuid The entity id wich adds the comment
 	 * @param int $commentGuid The entity id of the comment
	 *
	 * @return bool success
	 */
	public function addComment($objectGuid, $commentGuid);

	/**
	 * Removes a comment
	 *
	 * @param int $objectGuid The entity id wich deletes the comment
 	 * @param int $commentGuid The entity id of the comment	 
	 *
	 * @return bool success
	 */
	public function removeComment($objectGuid, $commentGuid);

	/**
	 * Returns the comment entities of this entity
	 *
	 * @return array the comments
	 */
	public function getComments();
}
