<?php

/**
 * Relation between child and parent forum
 *
 * @author killme
 */
class _SUB_FORUM_ extends ENeo4jRelationship
{
	/**
	 * @return _SUB_FORUM_ returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
