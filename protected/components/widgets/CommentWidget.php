<?php

namespace application\components\widgets;
use \Yii;

class CommentWidget extends \CWidget
{
	/**
	 * @property Neo4jNode $parent parent model
	 * @property Neo4jNode $_parent parent model
	 */
	private $_parent;
	
	public function setParent($parent)
	{
		if(!$parent instanceof \ICommentable)
			throw new CException('Model class '.get_class($parent).' is not ICommentable.');
		
		$this->_parent = $parent;
	}
	
	public function getParent()
	{
		return $this->_parent;
	}
	
	public function run()
	{
		echo '<h3>Comments</h2>';
		if (!$this->parent->hasAttribute('canComment') || $this->parent->canComment)
		{
			if(!$this->parent->canReadComment())
				echo '<div class="well">You are not authorized to view comments.</div>';
			else
			{
				//Speed up comments
				/**
				 * @todo remove this when with is implemented in Node class
				 */
				$id = $this->parent->getClassName().'.comments.'.$this->parent->id;
				if(($comments = Yii::app()->cache->get($id)) === false)
				{
					$comments = $this->parent->comments;
					$last = $this->parent->lastComment;
					Yii::app()->cache->set($id, $comments, 30);
				}
				
					if(count($comments) < 1)
					{
						/**
						 * @todo add bootstrap widget for first one to comment.
						 */
						Yii::app()->user->setFlash('noComments', 'Be the first one to comment!');
						$this->controller->widget ('bootstrap.widgets.TbAlert', array(
							'alerts' => array('noComments' => array('htmlOptions' => array('class' => 'alert-info'))),
						));
					}

					foreach($comments as $comment)
						$this->controller->renderPartial ('/comment/view', array('comment' => $comment));
				
								
				
				if($this->parent->canComment())
				{
					echo '<h4>Create comment</h4>';

					$model = new \application\models\Comment;

					$this->controller->renderPartial('/comment/add', array('model' => $model, 'parentId' => $this->parent->id, 'returnUrl' => \Yii::app()->request->requestUri));
				}
			}
		}
		else
		{
			echo '<div class="well">Comments are disabled.</div>';
		}
		
	}
}
