<?php

/**
 * Relation between forum and post/thread
 *
 * @author killme
 */
class _FORUM_HAS_THREAD_ extends ENeo4jRelationship
{
	/**
	 * @return _FORUM_HAS_THREAD_ returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
