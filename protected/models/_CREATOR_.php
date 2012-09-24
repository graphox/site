<?php

/**
 * Relation between user and a node that it created
 *
 * @author killme
 */
class _CREATOR_ extends ENeo4jRelationship
{
	/**
	 * @return _CREATOR_ returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }

	/**
	 * Initializes the time
	 */
	public function init()
	{
		parent::init();
		$this->date = time();
	}
	
	public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
			'date'	=>	array('type'=>'int'),
        ));
    }
}

?>
