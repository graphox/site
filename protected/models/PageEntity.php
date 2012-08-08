<?php

/**
 * Entity model for static pages (including the homepage).
 * May be commentable
 */

class PageEntity extends BaseEntity
{
	/**
	 * @var $type string the entity type, defaults to object, should not be changed.
	 */
	public $type = 'object';
	
	/**
	 * @var $status string the default status, draft.
	 */
	public $status = 'draft';
	
	/**
	 * @var $title string the title of the post.
	 */
	public $title;
	
	/**
	 * @var $content string the by markdown parsed content
	 */
	public $content;
	
	/**
	 * @var $source string the user entered content. is parsed by markdown on afterValidate
	 */
	public $source;
	
	/**
	 * @var $can_comment boolean if users should be able to comment
	 */
	public $can_comment;
		
	/**
	 * @var $metaMap array an array containing the metadata vars to include.
	 */
	protected $metaMap = array(
		'title',
		'content',
		'source',
		'can_comment',
	);

	/**
	 * Renders the source into html content.
	 * @return parent::beforeSave();
	 */
	protected function afterValidate()
	{
		$this->content = Yii::app()->contentMarkup->safeTransform($this->source);
		return parent::beforeSave();
	}
	
	/**
	 * @return array list or rules
	 */
	
	public function rules()
	{
		return array(
			array('title, source, access, status', 'required'),
			array('access', 'application.components.EntityAccessValidator'),
			array('status', 'application.components.EntityStatusValidator'),
		);
	}
	
	/**
	 * Initializes the record with default values and initializes timestamps.
	 */
	protected function init()
	{
		if($this->isNewRecord)
		{
			$this->subtype_id = EntityType::model()->findByAttributes(array('name' => 'page'))->id;
		
			#TODO: add multisite support
			$this->site_id = Entity::model()->findByAttributes(array('type' => 'site'))->id;

			$this->creator_id = Yii::app()->user->id;
			$this->owner_id = Yii::app()->user->id;
			$this->created_date = new CDbExpression('NOW()');
		}
		else
			$this->updated_date = new CDbExpression('NOW()');
	}
	
	/**
	 * finds comments on this entity
	 * @todo implement
	 * @return array list of comment Entity objects
	 */
	public function getComments()
	{
		return array();
	}
}

