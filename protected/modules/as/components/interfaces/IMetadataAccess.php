<?php

/**
 * An interface for the metadata accessor
 *
 * Example:
 *  $object = Yii::app()->user->entiry;
 *
 *  $object->meta->tags = array('tag', 'tag2', 'tag3');
 */
interface IMetadataAccess
{
	/**
	 * Initializes the accessor with the parent
	 *
	 * @attribute object &parent the parent object
	 */

	public function __construct(&$parent);

	/**
	 * sets metadata
	 */
	public function __set($var, $value);

	/**
	 * get metadata
	 *
	 * @return mixed the metadata value
	 */
	public function __get($var);
}
