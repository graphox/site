<?php

namespace application\models;

/**
 * Entity for comments
 *
 * @author killme
 * 
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $contentSource
 * @property int $createdTime
 * 
 * Relations:
 * @proprty \User $creator
 * @property array $voters
 */
class Comment extends \ENeo4jNode implements \ICommentable
{
	/**
	 * @return Comment returns class
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
            'title'				=>	array('type'=>'string'),
            'content'			=>	array('type'=>'string'),
			'contentSource'		=>	array('type'=>'string'),
			'createdTime'		=>	array('type'=>'integer'),
        ));
    }
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			//Basic rules
			array('title, contentSource', 'required'),
			
			array('title', 'length', 'max'=>100),
		);
	}
	
	/**
	 * @return array field names of the model attributes
	 */
	public function attributeLabels()
	{
		return array(
			'title'			=> \Yii::t('as.models.Comment', 'Title'),
			'contentSource'	=> \Yii::t('as.models.Comment', 'Comment'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'Commentable' => array(
				'class' => 'application.components.Commentable'
			)
		);
	}

	public function afterValidate()
	{
		parent::afterValidate();
		
		$this->content = \Yii::app()->contentMarkup->safeTransform($this->contentSource);
		$this->createdTime = time();
	}

	public function traversals()
    {
        return array(
            'creator'	=>	array(self::HAS_ONE, self::NODE, 'in("_CREATOR_")'),
			'voters'	=>	array(self::HAS_MANY, self::NODE, 'in("_VOTE_")'),
        );
    }
	
	public function afterSave()
	{
		parent::afterSave();
	
		if($this->creator === NULL)
			\Yii::app()->user->node->addRelationshipTo($this, '_CREATOR_');
	}
}