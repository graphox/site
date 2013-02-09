<?php

/**
 * An interface for objects that can be published and unpublished
 */
interface IPublishable
{
	/**
	 * publishes this entity
	 *
	 * @param int $objectGuid The entity id wich publishes the object
	 *
	 * @return bool success
	 */
	public function publish($objectGuid);

	/**
	 * unpublishes this entity
	 *
	 * @param int $objectGuid The entity id wich unpublishes the object
	 *
	 * @return bool success
	 */
	public function unPublish($objectGuid);
	
	/**
	 * Returns if the entity is published or not
	 *
	 * @return bool published
	 */
	public function isPublished();
}
