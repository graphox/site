<?php

/**
 * Class for Commentable models
 * @todo replace by a trait in the future
 */
class Commentable extends CModelBehavior
{
	/** @property ENeo4jNode $owner The owner model that this behavior is attached to. */
	
	/**
	 * Attatches the class to the parent.
	 * 
	 * @param ICommentable $owner
	 * @throws CException when $owner does not implement ICommentable
	 */
	public function attach($owner)
	{
		if(!($owner instanceof ICommentable))
			throw new CException('Model class '.get_clasS($owner).' does not implement ICommentable.');
		
		parent::attach($owner);
	}
	
	/**
	 * @return string the comment relation name
	 */
	public function commentRelationName()
	{
		return '_HAS_COMMENT_';
	}
	
	/**
	 * Adds a comment to this model
	 * @param \Comment $comment the comment
	 * @return bool success
	 */
	
	public function addComment(\application\models\Comment $comment)
	{
		$comment = $this->owner->addRelationshipTo($comment, $this->owner->commentRelationName());
		if($comment)
		{
			$owner->lastComment = $comment;
			return $owner->save(false);
		}
		
		return false;
	}
	
	/**
	 * Returns the comments of this model.
	 * @staticvar array $comments cached comment list
	 * @param bool $refresh refresh cached comment list
	 * @return array list of comments
	 */	
	public function getComments($refresh = false)
	{
		static $comments;
		
		if(!isset($comments) || $refresh)
		{
			
			
			$q = new \Everyman\Neo4j\Gremlin\Query(Yii::app()->neo4j,
				'posts = [];'.
				'g.v('.(int)$this->owner->id.').out(\''.$this->owner->commentRelationName().'\').aggregate(posts).iterate();'.
				'g.v('.(int)$this->owner->id.').out(\''.$this->owner->commentRelationName().'\').out(\''.$this->owner->commentRelationName().'\').aggregate(posts).iterate();'.
				'return posts.unique().sort{(it.createdTime)}'
			);

			$comments = \application\models\Comment::model()->findAllByQuery($q);
		}
		//throw new Exception("gettin commentz");
		
		return $comments;
	}
	
	/**
	 * @return bool whether the user is authorized to comment on this model.
	 */
	public function canComment()
	{
		return !\Yii::app()->user->isGuest;
	}
	
	/**
	 * @return bool whether the user is authorized to read comments from this model.
	 */
	public function canReadComment()
	{
		return true;
	}
	
	public function getEnabled()
	{
		return true;
	}
}