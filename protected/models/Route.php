<?php

namespace application\models;

/**
 * Entity for custom routes
 *
 * @author killme
 * 
 * @property int $id
 * @property string $from
 * @property string $to
 */
class Route extends \ENeo4jNode
{
	/**
	 * @return Route returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
	
	
	/**
	 * @return array, the properties of the node.
	 */
    public function properties()
    {
        return \CMap::mergeArray(parent::properties(),array(
            'from'				=>	array('type'=>'string'),
            'to'				=>	array('type'=>'string'),
        ));
    }
}