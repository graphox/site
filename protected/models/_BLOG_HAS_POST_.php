<?php

/**
 * Relation between blog and post
 *
 * @author killme
 */
class _BLOG_HAS_POST_ extends ENeo4jRelationship
{
	/**
	 * @return _BLOG_HAS_POST_ returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}

?>
