<?php

/**
 * Entity for alphaserv server nodes,
 * will be used for connection the nodes to the database and retrieving metadata
 *
 * @author killme
 * 
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string $description
 * @property string $descriptionSource
 * @property string $routeName
 */
class Node extends ENeo4jNode
{
	/**
	 * @return Node returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
	
	/**
	 * @return array, the properties of the node.
	 * @todo add server settings
	 */
    public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'name'				=>	array('type'=>'string'),
            'key'				=>	array('type'=>'string'),
			'description'		=>	array('type' =>'string'),
			'descriptionSource'	=>	array('type'=>'string'),
			'routeName'			=>	array('type'=>'string'),
			
			'enabled'			=>	array('type'=>'boolean'),
			'online'			=>	array('type'=>'boolean'),
        ));
    }
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			//Basic rules
			array('name, descriptionSource', 'required'),
			
			array('name', 'length', 'max'=>100),
			array('name', 'ENeo4jValidatorUnique'),

			array('routeName', 'safe', 'on' => 'admin'),
			array('enabled', 'in', 'range' => array(0, 1), 'on' => 'admin'),
			array('online', 'in', 'range' => array(0, 1), 'on' => 'admin'),
		);
	}

	public function afterValidate()
	{
		parent::afterValidate();
		
		$this->description = Yii::app()->contentMarkup->safeTransform($this->descriptionSource);
	}
	
	public function traversals()
    {
        return array(
        );
    }
	
	protected function beforeSave()
	{
		$this->routeName = preg_replace('/[^a-zA-Z0-9]/', '', $this->name);
		return parent::beforeSave();
	}
}
