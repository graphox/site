<?php

class CommentController extends Controller
{
	public function actionAdd()
	{
		if(Yii::app()->user->isGuest)
			$this->denyAccess();
		
		if(!isset($_SESSION['back_add_comment']))
			$_SESSION['back_add_comment'] = Yii::app()->request->getUrlReferrer();
		
		if(!isset($_GET['page-id']))
			throw new CHttpException(400, 'page id not provided');

		$page = Pages::model()->findByPK((int) $_GET['page-id']);

		if(!$page)
			throw new CHttpException(404, 'Could not find the requested page');			

		$model = new PageComments;
				
		#ajax validation
		if(isset($_POST['ajax']) && $_POST['ajax']==='page-comments-_commentform-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['PageComments']))
		{
			if(!isset($_POST['PageComments']['title']) || trim((string)$_POST['PageComments']['title']) == '')
				$_POST['PageComments']['title'] = 'RE: '.$page->title;

			$purifier = new CHtmlPurifier();

			$model->attributes = array(
				'page_id' => (int)$page->id,
				'user_id' => (int)Yii::app()->user->id,
				'posted_date' => new CDbExpression('NOW()'),
				'content' => isset($_POST['PageComments']['content']) ? /*$purifier->purify(*/$_POST['PageComments']['content']/*)*/ : null,
				'title' => $_POST['PageComments']['title']
			);
				
			if($model->validate())
			{
				if($model->save())
				{
					Yii::app()->user->setFlash('success', 'Successfully added your comment');

					$this->redirect($_SESSION['back_add_comment'].'#comment-'.(int)$model->id, false);
					unset ($_SESSION['back_add_comment']);
					Yii::app()->end();
				}

				else
					throw new CHttpexception(500, 'Could not save your comment into the database');
			}

		}
		else
			$model->title = 'RE: '.$page->title;

		$this->render('as.views.page._commentform', array('model' => $model, 'page' => $page));
	}
		
	public function actionReply()
	{
		if(Yii::app()->user->isGuest)
			$this->denyAccess();
		
		$_SESSION['back_add_comment'] = Yii::app()->request->getUrlReferrer();
		
		if(!isset($_GET['id']))
			throw new CHttpException(400, 'comment id not provided');

		$comment = PageComments::model()->with('page')->findByPK((int) $_GET['id']);

		if(!$comment)
			throw new CHttpException(404, 'Could not find the requested comment');			

		$model = new PageComments;

		$model->attributes = array(
			'page_id' => (int)$comment->page->id,
			'user_id' => (int)Yii::app()->user->id,
			'posted_date' => new CDbExpression('NOW()'),
			'title' => 'RE: '.$comment->title
		);
		
		foreach(explode("\n", $comment->content) as $line)
			$model->content .= trim($line) != '' ? "\n&gt; ".$line : '';

		$this->render('as.views.page._commentform', array('model' => $model, 'page' => $comment->page));		
	}
	
	public function actionVote()
	{
		if(Yii::app()->user->isGuest)
			$this->denyAccess();	
			
		if(!isset($_GET['comment-id']))
			throw new CHttpException(400, 'comment id not provided');
		
		if(!isset($_GET['way']) || ($_GET['way'] != 'sub' && $_GET['way'] != 'add'))
			throw new CHttpException(400, 'could not determine way of voting');

		$comment = PageComments::model()->with('page')->findByPK((int) $_GET['comment-id']);
		
		if(!$comment)
			throw new CHttpException(404, 'could not find comment');
		
		if(CommentVotes::model()->findByattributes(array('user_id' => Yii::app()->user->id, 'comment_id' => $comment->id)))
		{
			Yii::app()->user->setFlash('error', 'You cannot vote twice');
			$this->redirect(Yii::app()->request->getUrlReferrer());
		}
		
		$vote = new CommentVotes;
		$vote->user_id = Yii::app()->user->id;
		$vote->comment_id = $comment->id;
		$vote->value = $_GET['way'] == 'add' ? 1 : -1;
		
		if(!$vote->save())
			throw new CHttpexception(500, 'Could not save comment vote, '.print_r($vote->getError(), true));

		Yii::app()->user->setFlash('success', 'Successfully saved comment vote');
		$this->redirect(Yii::app()->request->getUrlReferrer());
	}
	
	public function actionFetch()
	{
		if(!isset($_GET['page-id']))
			throw new CHttpException(400, 'invalid page id');
		
		$comments = PageComments::model()->recently()->with('user')->findAllByAttributes(array('page_id' => $_GET['page-id']));
		$array = array();
		
		$p = new CHtmlPurifier();
		
		foreach($comments as $comment)
			$array[] = array(
				'id' => $comment->id,
				'url' => $this->createurl('//as/page', array('id' => (int)$_GET['page-id'])).'#comment-'.$comment->id,
				'title' => CHtml::encode($comment->title),
				'username' => CHtml::encode($comment->user ? $comment->user->username : ''),
				'html' => $p->purify($comment->content),
				'date' => $comment->posted_date
			);		
			
		echo json_encode($array);
	}
}

