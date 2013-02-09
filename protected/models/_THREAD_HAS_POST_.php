<?php

/**
 * Relation between forum and post/thread
 *
 * @author killme
 */
class _THREAD_HAS_POST_ extends ENeo4jRelationship
{
	/**
	 * @return _FORUM_HAS_POST_ returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
