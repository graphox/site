<?php

/**
 * Entity for blogs
 *
 * @author killme
 * @todo make some traits and interfaces.
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $descriptionSource
 *
 * @property string $blogTheme
 *
 * @property array $tags
 * 
 * @property bool $publish
 * @property string $routeName
 */
class Blog extends Node implements ICommentable
{
		/**
	 * @return Blogentity returns class
	 */
	public static function model($className = __CLASS__)
	{
        return parent::model($className);
    }
	
	/**
	 * @todo change tags into a relation to a tag.
	 * @return array, the properties of the node.
	 */
    public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'name'				=>	array('type'=>'string'),
            'description'		=>	array('type'=>'string'),
			'descriptionSource'	=>	array('type'=>'string'),
			'blogTheme'			=>	array('type'=>'string'),
			'tags'				=>	array('type'=>'string[]'),
			'publish'			=>	array('type'=>'boolean'),
			'routeName'			=>	array('type'=>'string'),
			'lastComment'		=> array('type'	=> 'int'),
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
			array('name', 'Neo4jValidatorUnique'),

			array('routeName, publish', 'safe', 'on' => 'admin'),
			array('publish', 'in', 'range' => array(0, 1), 'on' => 'admin'),
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
	
	public function attributeLabels()
	{
		return CMap::mergeArray(parent::attributeLabels(), array(
			'descriptionSource' => Yii::t('models.blog', 'Description')
		));
	}
	
	/**
	 * Returns a formated string version of the tags array.
	 * @return string formated string of tags
	 */
	public function getTagString()
	{
		return implode(', ', $this->tags);
	}

	public function afterValidate()
	{
		parent::afterValidate();
		
		$this->description = Yii::app()->contentMarkup->safeTransform($this->descriptionSource);
	}
	
	/**
	 * creates the relation from the blog to the post.
	 * @param BlogEntity $e
	 * @return _BLOG_HAS_POST_ the relationship
	 */
	public function addPost(BlogEntity $e)
	{
		return $this->addRelationshipTo($e, '_BLOG_HAS_POST_');
	}
	
	/**
	 * Only display my blogs.
	 * @return \Blog
	 */
	public function my()
	{
		return $this;
	}

	/**
	 * Returns the actions that can be done over either this blog or it's posts.
	 * Is used to set default access for users that do not own this blog.
	 * @return array action => defaultvalue pairs
	 */
	public function getAccessActions()
	{
		return array(
			'blog.admin.owner'	=> false,	# Is owner of this blog.
			'blog.admin.manage'	=> false,	# Can change general data like, access (except owner's), title, tags.
			'blog.admin.addUser'=> false,	# Can the user invite other users.
			
			'blog.edit'			=> false,	# Can edit all posts.
			'blog.edit.OWN'		=> false,	# Can edit own posts.
			'blog.create'		=> false,	# Can create posts.
			'blog.delete'		=> false,	# Can create posts.
				
			'blog.read'			=> true,		# Can read posts.
			'blog.comment'		=> true,		# Can comment on posts / like posts.
		);
	}
	
	/**
	 * @return array the access the one who created this blog should receive.
	 */
	public static function getOwnerAccessActions()
	{
		return array(
			'blog.admin.owner'	=> true,
			'blog.admin.manage'	=> true,
			'blog.admin.addUser'=> true,
			
			'blog.edit'			=> true,
			'blog.edit.OWN'		=> true,
			'blog.create'		=> true,
			'blog.delete'		=> true,
			
			'blog.read'			=> true,	
			'blog.comment'		=> true,	
		);		
	}
	
	/**
	 * Returns the actions that may be posted on the user's timeline.
	 * @return array action => classname pairs
	 */
	public static function getTimelineActions()
	{
		return array(
			'blog.create' => 'CreateBlogPostAction', # Publish blogs on the user's timeline
		);
	}
	
	/**
	 * @todo implement
	 * @return boolean has the current user a relation to this blog.
	 */
	public function isOwner()
	{
		try
		{
			$this->getOwnerRelation();
			return true;
		}
		catch(CException $e)
		{
			return false;
		}
	}
	
	/**
	 * Returns the relation with the current user
	 * @todo implement
	 * @return \_BLOG_OWNER_
	 * @throws CException when no relation was found.
	 */
	public function getOwnerRelation()
	{
		foreach($this->ownerRelations as $owner)
		{
			if($owner->startNode->id === Yii::app()->user->node->id)
				return $owner;
		}
		
		throw new CException('could not find a relation!');
	}
	
	/**
	 * @param string $action the action
	 * @return boolean wether the user is allowed to perform the specified action
	 * @throws CException when nothing is found on the relation and no defaults are available
	 */
	public function hasAccess($action)
	{
		if($this->isOwner())
		{
			foreach($this->ownerRelation->rules as $rule)
			{
				if($rule === $action)
					return true;
				elseif($rule === '-'.$action)
					return false;
			}
		}
		
		if(isset($this->accessActions[$action]))
			return $this->accessActions[$action];
		else
			throw new CException('Invalid access action.');
	}
	
	public function traversals()
    {
        return array(
            'owners'		=>	array(self::HAS_MANY, 'User',			'relation' => '.out("_BLOG_OWNER_")'),
			'ownerRelations'=>	array(self::HAS_MANY, '_BLOG_OWNER_',	'relation' => 'inE("_BLOG_OWNER_")'),
			'posts'			=>	array(self::HAS_MANY, 'BlogEntity',		'relation' => 'out("_BLOG_HAS_POST_")'),
        );
    }
	
	protected function beforeSave()
	{
		$this->routeName = preg_replace('/[^a-zA-Z0-9]/', '', $this->name);
		return parent::beforeSave();
	}
}
