<?php

namespace application\components\widgets;
use \Yii;

class CommentWidget extends \CWidget
{
	/**
	 * @property \ENeo4jNode $parent parent model
	 * @property \ENeo4jNode $_parent parent model
	 */
	private $_parent;
	
	public function setParent(\ENeo4jNode $parent)
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
				if(count($this->parent->comments) < 1)
				{
					Yii::app()->user->setFlash('noComments', 'Be the first one to comment!');
					$this->controller->widget ('bootstrap.widgets.BootAlert', array(
						'keys' => array('noComments'),
						'template' => '<div class="alert alert-block alert-info{class}"><a class="close" data-dismiss="alert">&times;</a>{message}</div>',
					));
				}
				
				foreach($this->parent->comments as $comment)
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
