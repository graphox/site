<?php
class _FRIEND_ extends ENeo4jRelationship
{
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'accepted'	=>	array('type'=>'boolean'),
			'created'	=>	array('type'=>'int'),
        ));
    }

    public function rules()
    {
        return array(
        );
    }
}