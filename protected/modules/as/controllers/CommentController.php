<?php

class CommentController extends CController
{
	public function actionAdd()
	{
		if(Yii::app()->user->isGuest)
		{
			throw new CHttpException(403, 'Please login to may be able to post');
		}
		
		if(isset($_GET['page-id']))
		{
			$page = Pages::model()->findByPK((int) $_GET['page-id']);
			
			if(is_object($page))
			{
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
						'content' => $purifier->purify($_POST['PageComments']['content']),
						'title' => $_POST['PageComments']['title']
					);
				
					if($model->validate())
					{
						if($model->save())
							$this->redirect(array('page/index', 'page-id' => (int)$page->id , '#' => 'comment-'.(int)$model->id));

						else
							throw new CHttpexception(500, 'Could not save your comment into the database');
					}
				}
				else
					$model->attributes = array('title' => 'RE: '.$page->title);
				$this->render('as.views.page._commentform',array('model' => $model, 'page' => $page));
				Yii::app()->end();
			}
		}

		throw new CHttpException(400, 'invalid page id');
	}
	
	public function actionFetch()
	{
		if(!isset($_GET['page-id']))
			throw new CHttpException(400, 'invalid page id');
		
		$comments = PageComments::model()->recently()->with('user')->findAllByAttributes(array('page_id' => $_GET['page-id']));
		$array = array();
		
		foreach($comments as $comment)
			$array[] = array(
				'id' => $comment->id,
				'url' => $this->createurl('page', array('page-id' => (int)$_GET['page-id'])).'#comment-'.$comment->id,
				'title' => CHtml::encode($comment->title),
				'username' => CHtml::encode($comment->user->username),
				'html' => $comment->content,
				'date' => $comment->posted_date
			);		
			
		echo json_encode($array);
	}
}

