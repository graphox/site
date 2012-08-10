<?php

/**
 * Entity model for comments posted on other entities
  */

class CommentEntity extends BaseEntity
{
	/**
	 * @var $type string the entity type, defaults to object, should not be changed.
	 */
	public $type = 'object';
	
	/**
	 * @var $status string the default status, draft.
	 */
	public $status = 'published';
	
	/**
	 * @var string user's access on the object, is the same as the parent. set to public here.
	 */
	public $access = 'public';
	
	/**
	 * @var $content string the by markdown parsed content
	 */
	public $content;
	
	/**
	 * @var $source string the user entered content. is parsed by markdown on afterValidate
	 */
	public $source;
	
	/**
	 * @var $metaMap array an array containing the metadata vars to include.
	 */
	protected $metaMap = array(
		'content',
		'source',
	);
	
	/**
	 * @var $parent integer the parent entity
	 */
	public $parent_id;

	/**
	 * Renders the source into html content.
	 * @return parent::beforeSave();
	 */
	protected function afterValidate()
	{
		$this->content = Yii::app()->contentMarkup->safeTransform($this->source);
	
		return parent::afterValidate();
	}

	
	
	/**
	 * @return array list or rules
	 */
	
	public function rules()
	{
		return array(
			array('source, parent_id', 'required'),
			array('source', 'length', 'min' => 3),
			array('source', 'validateParent'),
			array('source', 'validateParentAccess'),
		);
	}
	
	/**
	 * Validates the parent object
	 * @attribute $attribute string the attribute name
	 */
	public function validateParent($attribute)
	{
		if(!$this->hasErrors() && $this->getParent() === NULL)
			$this->addError ($attribute, 'Invalid parent, please contact an administrator.');
	}

	/**
	 * Validates the access on the parent
	 * @attribute $attribute string the attribute name
	 */
	public function validateParentAccess($attribute)
	{
		if(
				!$this->hasErrors()
				&& (
					$this->getParent()->can_comment !== true
					or $this->getParent()->can('comment.create') !== true
					))
			$this->addError ($attribute, 'Access denied, not allowed to create a comment.');
	}
	
	/**
	 * Initializes the record with default values and initializes timestamps.
	 */
	protected function init()
	{
		if($this->isNewRecord)
			$this->subtype_id = EntityType::model()->findByAttributes(array('name' => 'comment'))->id;
		
		parent::init();
	}
	
	/**
	 * finds comments on this entity
	 * @return BaseEntity the parent object
	 */
	public function getParent()
	{
		static $parent;
		
		if(!isset($parent) or $parent === NULL)
		{
			$parent = Entity::model()->findByPk($this->parent_id);
			if($parent instanceof Entity)
				$parent = $parent->typeModel;
		}
				
		return $parent;
	}
	
	/**
	 * Adds the comment relation
	 */
	public function afterSave()
	{
		$this->parent->addRelation('comment', $this);
		
		parent::afterSave();
	}
}

