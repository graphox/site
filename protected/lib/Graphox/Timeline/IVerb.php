<?php

namespace Graphox\Timeline;

interface IVerb
{

	/**
	 * @return \DateTime the created date
	 */
	public function getCreatedDate();

	/**
	 * Sets de date of the verb
	 */
	public function setCreatedDate(\DateTime $date);
	
	/**
	 * Sets whether the verb is published
	 * @param bool $published whether the verb has been published
	 */
	public function setIsPublished($published);

	/**
	 * Checks if a verb has been published
	 * @see isPublished
	 * @return bool
	 */
	public function getIsPublished();

	/**
	 * Checks if a verb has been published
	 * @see getIsPublished
	 * @return bool
	 */
	public function isPublished();

	/**
	 * Sets whether the verb is "deleted"
	 * @param bool $deleted whether the verb has been "deleted"
	 */
	public function setIsDeleted($deleted);

	/**
	 * Checks if a verb has been "deleted"
	 * @see isDeleted
	 * @return bool
	 */
	public function getIsDeleted();

	/**
	 * Checks if a verb has been "deleted"
	 * @see getIsDeleted
	 * @return bool
	 */
	public function isDeleted();

	/**
	 * Manual check if the verb is visible
	 * @return bool
	 */
	public function isVisible();
}

