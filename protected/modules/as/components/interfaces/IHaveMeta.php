<?php

/**
 * An interface for objects wich have metadata
 */
interface IHaveMeta
{
	/**
	 * returns an accessor object for the object's metadata
	 *
	 * @return object metadata accessor
	 */
	public function getMetaData();
}
