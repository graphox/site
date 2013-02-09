<?php

/**
 * Entity for pages
 *
 * @author killme
 * @todo make some traits and interfaces.
 * 
 * @property int $id
 * @property string $name
 * @property string $content
 * @property string $contentSource
 * @property string $routeName
 * 
 * Relations:
 * @proprty array $creators
 * @property array $updaters
 */
class Page extends Neo4jNode
{
		/**
	 * @return Blogentity returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
	
	/**
	 * @return array, the properties of the node.
	 */
    public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'name'				=>	array('type'=>'string'),
            'content'			=>	array('type'=>'string'),
			'contentSource'		=>	array('type'=>'string'),
			'routeName'			=>	array('type'=>'string'),
        ));
    }
	
		/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			//Basic rules
			array('name, contentSource', 'required'),
			
			array('name', 'length', 'max'=>100),
			array('name', 'ENeo4jValidatorUnique', 'on' => 'insert'),
		);
	}

	public function afterValidate()
	{
		parent::afterValidate();
		
		$this->content = Yii::app()->contentMarkup->safeTransform($this->contentSource);
	}

	public function traversals()
    {
        return array(
            'creators'	=>	array(self::HAS_MANY, self::NODE, 'in("_CREATOR_")'),
			'updaters'	=>	array(self::HAS_MANY, self::NODE, 'in("_UPDATER_")'),
        );
    }
	
	protected function beforeSave()
	{
		$this->routeName = preg_replace('/[^a-zA-Z0-9]/', '', $this->name);
		return parent::beforeSave();
	}
	
	public function afterSave()
	{
		parent::afterSave();
	
		
		if(count($this->creators) == 0)
			Yii::app()->user->node->addRelationshipTo($this, '_CREATOR_');
		else
			Yii::app()->user->node->addRelationshipTo($this, '_UPDATER_');
	}
}
