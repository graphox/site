<?php

/**
 * Entity model for user profiles
 * May be commentable
 */

class ProfileEntity extends BaseEntity
{
	/**
	 * @var $country the country of the user
	 */
	public $country;
	
	/**
	 * @var $homepage the homepage of the user
	 */
	public $homepage;
	
	/**
	 * @var $source string the user entered content. is parsed by markdown on afterValidate
	 */
	public $source;
	
	/**
	 * @var $content the parsed content
	 */
	public $content;
	
	/**
	 * @var $can_comment boolean if users should be able to comment
	 */
	public $can_comment;
		
	/**
	 * @var $metaMap array an array containing the metadata vars to include.
	 */
	protected $metaMap = array(
		'country',
		'homepage',
		'source',
		'can_comment',
		'content'
	);

	/**
	 * @var string the typename of the entity.
	 */
	public $_name = 'page';
	
	/**
	 * Renders the source into html content.
	 * @return parent::afterValidate();
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
			array('access, status', 'required'),
			array('country, homepage, source', 'safe'),
			array('access', 'application.components.EntityAccessValidator'),
		);
	}
	
	/**
	 * Initializes the record with default values and initializes timestamps.
	 */
	protected function init()
	{
		parent::init();
		
		$this->status = Entity::STATUS_PUBLISHED;
		
		if(isset($this->can_comment))
			$this->can_comment = $this->can_comment === '1';
	}
	
	/**
	 * finds comments on this entity
	 * @return array list of comment Entity objects
	 */
	public function getComments()
	{
		if($this->can_comment !== true)
			return array();
		else
		{
			return $this->getRelations('comment');
		}
	}
	
	/* @var $user User the user bound to this object*/
	/**
	 * Returns the user object bound to this class
	 * @staticvar User $user the cached user
	 * @return User the user
	 * @throws CException('User not found.')
	 */
	public function getUser()
	{
		static $user;
		
		if(!isset($user))
			$user = User::model()->findByAttributes(array('entity_id' => $this->id));
		
		if($user === null)
			throw new CException('User not found.');
		
		return $user;
	}
	
	/* @var $name string the name of an user */
	/**
	 * @throws CException('User not found.')
	 * @return string returns the user's name
	 */
	public function getName()
	{
		return $this->user->username;
	}
}

