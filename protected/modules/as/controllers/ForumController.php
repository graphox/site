<?php
/**
 * Forum controller Home page
 */
class ForumController extends Controller
{

	
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();
		
		// Add page breadcrumb and title
		$this->pageTitle = Yii::t('forum', 'Forum');
		$this->breadcrumbs = array(
			'Forum' => array('index'),
		);
		
		
			$obj = AccessControl::GetObjectByName('forum.overview');
			
			if(!$obj)
			{
				#since this is an admin object
				if(($admin_obj = AccessControl::GetObjectByName('forum.')) == false)
				{
					$admin_obj = AccessControl::AddObject('forum', null, -1);
		
					if(($group = AccessControl::getGroup('user')) == false)
					{
						$group = AccessControl::addGroup('user');
					}
		
					AccessControl::giveAccess($admin_obj, $group, Access::create(true, true, true, true));
				}
		
				$obj = AccessControl::AddObject('forum.overview', $admin_obj, -1);
			}
		
    }

	/**
	 * Index action
	 */
    public function actionIndex()
    {
		if((($access = AccessControl::GetAccess('forum.overview')) === false) || $access->read != 1)
			$this->denyAccess();
				
		//TODO: search
		$criteria = new CDbCriteria();
		$criteria->select = '*';
		
				
		$count = ForumTopic::model()->count($criteria);
		$pages = new CPagination($count);

		// results per page
		$pages->pageSize = isset($_GET['per-page']) ? (int)$_GET['per-page'] : 10;
		$pages->applyLimit($criteria);
		$models = ForumTopic::model()->findAll($criteria);
		
		$forums_model = Forum::model()->with('forums', 'aclObject')->findAllByAttributes(array('parent_id' => null));
		
		$forums = array();
		
		foreach($forums_model as $forum)
		{
			$forum_access = AccessControl::GetUserAccess($forum->aclObject);
			
			if($forum_access && $forum_access->read == 1)
			{
				$children = array();
				foreach($forum->forums as $child)
				{
					$forum_child_access = AccessControl::GetUserAccess($forum->aclObject);
			
					if($forum_child_access && $forum_child_access->read == 1)
					{
						$children[] = array('id'=> $child->id,  'name' => $child->name, 'description' => $child->description, 'children' => array());
					}
				}
				
				$forums[] = array( 'id'=> $forum->id, 'name' => $forum->name, 'description' => $forum->description, 'children' => $children);
			}
		}		
	
		// Grab topics by date
		/*
		$criteria = new CDbCriteria;
		$criteria->condition = 'visible=:visible OR visible=:mod';
		$criteria->params = array(':visible' => 1, ':mod' => Yii::app()->user->checkAccess('op_forum_post_topics') ? 0 : 1 );
		*/

		/*
		$count = ForumTopics::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::TOPIC_PAGE_SIZE;
		$pages->route = '/forum/index';
		$pages->params = array('lang'=>false);
	
		$pages->applyLimit($criteria);

		$rows = ForumTopics::model()->byDate()->with(array('postscount', 'author','lastauthor'))->findAll($criteria);
		*/
	
        $this->render('index', array('can' => $access, 'models' => $models, 'pages' => $pages, 'forums' => $forums));
    }
    
    public function actionAddforum()
    {
    	$can = AccessControl::GetAccess('forum.overview');
    	
    	if(!$can->update)
    		$this->denyAccess();
    	
    	$model = new Forum;
		if(isset($_GET['parent-id']) && $_GET['parent-id'] != -1)
			$model->parent_id = $_GET['parent-id'];
		
		if(isset($_POST['Forum']))
		{
			$model->attributes = $_POST['Forum'];
			if($model->validate())
			{
				$acl = $model->initAcl();
				if($model->save(false))
				{
					$model->updateAcl($acl);
					Yii::app()->user->setFlash('edit-forum', 'successfully updated forum.');
					Yii::app()->user->setFlash('edit-forum:id', $model->id);
				}
			}
		}
		
		$this->render('add_forum', array('model' => $model));
    }
    
    
    public function actionViewforum()
    {
    	if(isset($_GET['id']))
	    	$forum = Forum::model()->findByPk($_GET['id']);
    	elseif(isset($_GET['forum']))
			$forum = Forum::model()->findByAttributes(array('name' => $_GET['forum']));
		else
			throw new CHttpException(400, 'no forum name/id was given.');
		
		if(!$forum)
			throw new CHttpException(404, 'Invalid forum id/name');
		
		if(!isset($_GET['id']) || !isset($_GET['forum']))
			$this->redirect(array('//as/forum/viewforum', 'id' => $forum->id, 'forum' => $forum->name));
		
		$access = AccessControl::GetUserAccess($forum->aclObject);

		if($access->read != 1)
			throw new CHttpException(403, Yii::t('forum', 'You don\'t have permission to view this forum.'));
		
		//TODO: search
		$criteria = new CDbCriteria();
		$criteria->select = '*';
		$criteria->condition = 'forum_id=:forumID';
		$criteria->params = array(':forumID'=>$forum->id);
						
		$count = ForumTopic::model()->count($criteria);
		$pages = new CPagination($count);

		// results per page
		$pages->pageSize = isset($_GET['per-page']) ? (int)$_GET['per-page'] : 10;
		$pages->applyLimit($criteria);
		$models = ForumTopic::model()->findAll($criteria);
		
		$this->breadcrumbs = array(
			'Forum' => array('index'),
			$forum->name => array()
		);
		
		
		$this->render('view_forum', array('can' => $access, 'models' => $models, 'pages' => $pages, 'forum' => $forum));
    }
	
	/**
	 * Add topic action
	 */
	public function actionAddtopic()
	{
		if(!isset($_GET['forum']))
			throw new CHttpException(400, 'no forum name/id was given.');
		
		$forum = Forum::model()->findByPk(isset($_GET['forumid']) ? $_GET['forumid'] : $_GET['forum']);
		
		if(!$forum && ($forum = Forum::model()->findByAttributes(array('name' => $_GET['forum']))) === null)
		{
			throw new CHttpException(404, Yii::t('forum', 'Could not find that forum, did you enter the correct name/id ?'));
		}
		
		$access = AccessControl::GetUserAccess($forum->aclObject);
		
		if(Yii::app()->user->isGuest || $access->write != 1)
			throw new CHttpException(403, Yii::t('forum', 'You don\'t have permission to post a topic in this forum.'));
			
		$model = new ForumTopic;
		
		// Did we submit the form?
		if( isset($_POST['ForumTopic']) )
		{
			$model->attributes = $_POST['ForumTopic'];
			$model->status = 0;
			$model->forum_id = $forum->id;
			$model->acl_object_id = -1;
			$model->user_id = Yii::app()->user->id;
			$model->posted_date = new CDbExpression('NOW()');
			
			if($model->validate())
			{
				if(($acl_obj = AccessControl::GetObjectByName(substr('forum.'.$forum->name.'.topic.'.$model->id, 0, 50))) == false)
					$acl_obj = AccessControl::AddObject(substr('forum.'.$forum->name.'.topic.'.$model->id, 0, 50), null, -1);
				
				$model->acl_object_id = $acl_obj->id;
			}
			
			if( $model->save() )
			{
				#change the acl name
				$acl_obj->name = substr('forum.'.$forum->name.'.topic.'.$model->id, 0, 50);
				if(!$acl_obj->save())
					throw new Exception(print_r($acl_obj->getErrors(), true));
			
				Yii::app()->user->setFlash('success', Yii::t('forum', 'Thank You. Your topic created.'));
				$this->redirect(array('//as/forum/viewtopic', 'topicid'=> $model->id, 'topic' => $model->title));
			}
		}
		
		// Add page breadcrumb and title
		$this->pageTitle = Yii::t('forum', 'Create A Topic');
		$this->breadcrumbs = array(
			'Forum' => array('index'),
			$forum->name => array('//as/forum/viewforum', 'id' => $forum->id, 'name' => $forum->name),
			Yii::t('forum', 'Create A Topic') => array()
		);
		
		
		// Render
		$this->render('as.views.edit', array('model'=>$model, 'header' => 'Create a topic'));
	}
	
	/**
	 * View Topic Action
	 */
	public function actionViewtopic()
	{
    	if(isset($_GET['topicid']))
	    	$topic = ForumTopic::model()->findByPk($_GET['topicid']);
    	elseif(isset($_GET['topic']))
			$topic = ForumTopic::model()->findByAttributes(array('title' => $_GET['topic']));
		else
			throw new CHttpException(400, 'no topic name/id was given.');
		
		if(!$topic)
			throw new CHttpException(404, Yii::t('forum', 'Could not find that topic, did you enter the correct name/id ?'));
		
		if(!isset($_GET['topicid']) || !isset($_GET['topic']))
			$this->redirect(array('//as/forum/viewtopic', 'topicid'=> $topic->id, 'topic' => $topic->name));
		
		$access = AccessControl::GetUserAccess($topic->aclObject);
		
		if($access->read != 1)
			throw new CHttpException(403, Yii::t('forum', 'You don\'t have permission to read this topic.'));
			
		// Did we add a new post?
		$newPost = new ForumMessage;
		if( isset($_POST['ForumMessage']) )
		{
			// Make sure we have access
			if($access->write != 1)
				throw new CHttpException(403, Yii::t('forum', 'Sorry, You are not allowed to perform that operation.'));
				
			$newPost->attributes = $_POST['ForumMessage'];
			$newPost->topic_id = $topic->id;
			$newPost->date_changed = $newPost->date_added = new CDbExpression('NOW()');
			$newPost->user_id = Yii::app()->user->id;
				
				if($newPost->save())
				{
					/* TODO
	
					// Send notifications to the ones subscribed
					$topicSubscribtions = TopicSubs::model()->with(array('user', 'topic'))->findAll('topicid=:topicid', array( ':topicid' => $topic->id ));
					
					// Loop and email
					if( $topicSubscribtions )
					{
						foreach( $topicSubscribtions as $sub )
						{
							$email = Yii::app()->email;
							
							// We skip the user that actually posted the new post
							if( $sub->userid == Yii::app()->user->id )
							{
								continue;
							}
							
							// Email to the users email address
							$email->subject = Yii::t('forum', "New post in a topic you are subscribed to '{title}'", array( '{title}' => $sub->topic->title ) );
							$email->to = $sub->user->email;
							$email->from = Yii::app()->params['emailout'];
							$email->replyTo = Yii::app()->params['emailout'];
							$email->message = Yii::t('forum', "Dear {user}, <br /><br />A new post was made by '{author}' in the topic '{topic}' you are subscribed to. To visit the topic please click the following link<br /><br />{link}<br /><br />
																		  <small>To unsubscribe from receiving updates for this topic please click the following link {unlink}</small>.<br /><br />
																		  Regards, The {name} Team.", 
																		  array( 
																		  		'{user}' => $sub->user->username, 
																		  		'{author}' => $newPost->author->username,
																		  		'{topic}' => CHtml::encode( $sub->topic->title ),
																		  		'{link}' => $this->createAbsoluteUrl('/forum/topic/' . $sub->topic->id . '-' . $sub->topic->alias, array('lang'=>false)),
																		  		'{unlink}' => $this->createAbsoluteUrl('/forum/unsubscribe', array( 'id' => $sub->topic->id, 'lang' => false ) ),
																		  		'{name}' => Yii::app()->name,
																		  		));
							$email->send();
						}
					}*/
					
					Yii::app()->user->setFlash( 'success', Yii::t('forum', 'Thank You. Your post was submitted.') );
					$this->redirect(array('/as/forum/viewtopic/', 'topic' => $topic->id, 'page' => $_POST['lastpage'], '#' => 'post' . $newPost->id));
				}
		}
			
		#Increase the views count TODO
		#$topic->views++;
		#$topic->update();
			
		// Grab posts
		$criteria = new CDbCriteria;
		$criteria->condition = 'topic_id = :tid';
		$criteria->params = array(':tid' => $topic->id);
		$criteria->order = 'posted_date';

		$count = ForumMessage::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = isset($_GET['per-page'])?max((int)$_GET['per-page'],3) : 20;
		#$pages->route = '/as/forum/topic/'.$topic->id . '-' . $topic->alias;
		#$pages->params = array('lang'=>false);

		$pages->applyLimit($criteria);

		$models = ForumMessage::model()->byDateAsc()->with(array('user'))->findAll($criteria);
			
		// Show titles and nav
		$this->pageTitle = Yii::t('forum', 'viewing topic: {title}', array('{title}'=> $topic->title ));
		$this->breadcrumbs = array(
			'Forum' => array('index'),
			$topic->forum->name => array('//as/forum/viewforum', 'id' => $topic->forum->id, 'forum' => $topic->forum->name),
			Yii::t('forum', 'viewing topic: {title}', array('{title}'=> $topic->title)) => array()
		);

		$markdown = new CMarkdownParser;
		
		# Are we subscribed into this topic?
		# $subscribed = TopicSubs::model()->find('topicid=:topicid AND userid=:userid', array( ':topicid' => $topic->id, ':userid' => Yii::app()->user->id ) );
			
		#Render
		$this->render('view_topic', array( 'topic' => $topic, 'markdown' => $markdown, 'models' => $models, 'newPost' => $newPost, 'count' => $count, 'pages' => $pages, 'can' => $access ));
	}
	
	function actionReply()
	{
		if(!isset($_GET['topic']))
			throw new CHttpException(404, 'Could not find topic.');
		
		$topic = ForumTopic::model()->findByPk($_GET['topic']);

		if(!$topic)
			throw new CHttpException(404, Yii::t('forum', 'Could not find that topic, did you enter the correct name/id ?'));
		
		$access = AccessControl::GetUserAccess($topic->aclObject);
		
		if($access->write != 1)
			throw new CHttpException(403, Yii::t('forum', 'You don\'t have permission to reply on this topic.'));
		
		$model = new ForumMessage;
		
		if(isset($_POST['ForumMessage']))
		{
			$model->attributes = $_POST['ForumMessage'];
			if($model->validate())
			{
				$model->user_id = Yii::app()->user->id;
				$model->topic_id = $topic->id;
				$model->posted_date = new CDbExpression('NOW()');
				
				if($model->save())
				{
					Yii::app()->user->setFlash('add-reply-message', 'Successfully added reply.');
					$this->redirect(array('//as/forum/viewtopic', 'topicid'=> $topic->id, 'topic' => $topic->title));
				}
			}
		}
		
		$this->render('as.views.edit', array('model' => $model, 'action' => array()));
	}
}
