<?php

class PageController extends Controller
{
	public function actionIndex()
	{
		$uri = Yii::app()->request->getQuery('path');
		
		if(!isset($uri))
			$uri = '/';
			
		if($uri[0] !== '/')
			$uri = '/'.$uri;
		
		$pages = Pages::model()->with('editor', 'aclObject', 'pageComments')->findAllByAttributes(array('uri' => $uri));
		
		$found = false;
		
		foreach($pages as $page)
			if($page->module === 'web')
			{
				$found = true;
				break;
			}
		
		if($found === false)
			throw new CHttpException(400, 'Page not found.');
	
		new AccessControl; //::GetUserAccess($page->aclObject); #TODO
	
		$access = new Access;
		$access->read = true;
		$access->write = true;
		$access->update = true;
		$access->delete = true;
		
		if(!$access->read)
			throw new CHttpException(403, 'Access Denied, you are not allowed to see this page.');
		
		$this->render('page', array(
			'page_id' => $page->id,
			'content' => $page->content,
			'editor' => $page->editor,
			'comments' => $page->pageComments,
			'can_comment' => $access->update,
			
			#can edit page (and comments?)
			'can_edit' => $access->write,
			'can_delete' => $access->delete			
		));
	}
	
	public function actionAction()
	{
		if(!isset($_GET['id']) || !isset($_GET['action']))
			throw new CHttpException(400, 'Malformated URI, id or action missing');
		
		switch($_GET['action'])
		{
			case 'vote':
				$value = 0;
				if(isset($_GET['add']))
					$value = 1;
				elseif(isset($_GET['sub']))
					$value = -1;
				else
					throw new CHttpException(400, 'Malformated URI, add and sub missing');
				
				$votes = CommentVotes::model()->findAllByAttributes(array('comment_id' => (int)$_GET['reaction'], 'user_id' => (int)Yii::app()->user->id));
				
				if(count($votes) > 0)
				{
					echo 'you can only vote once';
					Yii::app()->end();
				}
				
				
				$vote = new CommentVotes;
				$vote->comment_id = $_GET['reaction'];
				$vote->user_id = Yii::app()->user->id;
				$vote->value = $value;
		
				if(!$vote->save())
					throw new exception(print_r($vote->getErrors(), true));

				break;
		}
	}
}

