<?php
/**
 * Forum controller Home page
 */
class ForumController extends Controller {
	
	/**
	 * Page size constants
	 */
	const TOPIC_PAGE_SIZE = 50;
	const POST_PAGE_SIZE = 50;
	
	public $installed = false;
	
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();
		
		// Add page breadcrumb and title
		$this->pageTitle = Yii::t('forum', 'Forum');
		$this->breadcrumbs = array(
			array('Forum', array('index')),
		);
		
		if(!$this->installed)
		{
			$obj = AccessControl::GetObjectByName('Forum::Overview');
			
			if(!$obj)
			{
				#since this is an admin object
				if(($admin_obj = AccessControl::GetObjectByName('Forum')) == false)
				{
					$admin_obj = AccessControl::AddObject('Forum', null, -1);
		
					if(($group = AccessControl::getGroup('user')) == false)
					{
						$group = AccessControl::addGroup('user');
					}
		
					AccessControl::giveAccess($admin_obj, $group, Access::create(true, true, true, true));
				}
		
				$obj = AccessControl::AddObject('Forum::Overview', $admin_obj, -1);
			}
		}
    }

	/**
	 * Index action
	 */
    public function actionIndex()
    {
		if((($access = AccessControl::GetAccess('Forum::Overview')) === false) || $access->read != 1)
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
			$access = AccessControl::GetUserAccess($forum->aclObject);
			
			if($access && $access->read == 1)
			{
				$children = array();
				foreach($forum->forums as $child)
				{
					$access = AccessControl::GetUserAccess($forum->aclObject);
			
					if($access && $access->read == 1)
					{
						$children[] = array( 'name' => $child->name, 'description' => $child->description, 'children' => array());
					}
				}
				
				$forums[] = array( 'name' => $forum->name, 'description' => $forum->description, 'children' => $children);
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
    
    public function actionViewforum()
    {
		if(!isset($_GET['forum']))
			throw new CHttpException(400, 'no forum name/id was given.');
		
		$forum = Forum::model()->findByPk(isset($_GET['forumid']) ? $_GET['forumid'] : $_GET['forum']);
		
		if(!$forum && ($forum = Forum::model()->findByAttributes(array('name' => $_GET['forum']))) === null)
		{
			throw new CHttpException(404, Yii::t('forum', 'Could not find that forum, did you enter the correct name/id ?'));
		}
		
		$access = AccessControl::GetUserAccess($forum->aclObject);

		if($access->read != 1)
			throw new CHttpException(403, Yii::t('forum', 'You don\'t have permission to view this forum.'));
		
		//TODO: search
		$criteria = new CDbCriteria();
		$criteria->select = '*';
		$criteria->condition = 'forum_id=:forumID';
		$criteria->params = array(':forumID'=>(int)$forum->id);
		
				
		$count = ForumTopic::model()->count($criteria);
		$pages = new CPagination($count);

		// results per page
		$pages->pageSize = isset($_GET['per-page']) ? (int)$_GET['per-page'] : 10;
		$pages->applyLimit($criteria);
		$models = ForumTopic::model()->findAll($criteria);
		
		$this->render('view_forum', array('can' => $access, 'models' => $models, 'pages' => $pages, 'forum' => $forum));
    }
	
	/**
	 * Add topic action
	 */
	public function actionaddtopic()
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
			
		$model = new AddTopicForm;
		
		// Did we submit the form?
		if( isset($_POST['AddTopicForm']) )
		{
			$model->attributes = $_POST['AddTopicForm'];
			$model->status = 0;
			$model->forum_id = $forum->id;
			$model->acl_object_id = -1;
			
			if($model->validate())
			{
				#if the rest is ok then we add a object, TODO: is this executed only once? then check uniqueness
				if(($acl_obj = AccessControl::GetObjectByName(substr('Forum::'.$forum->name.'::topic::'.$model->name.'::'.md5($model->description), 0, 50))) == false)
					$acl_obj = AccessControl::AddObject(substr('Forum::'.$forum->name.'::topic::'.$model->name.'::'.md5($model->description), 0, 50), null, -1);
				
				$model->acl_object_id = $acl_obj->id;
			}
			
			if( $model->save() )
			{
				Yii::app()->user->setFlash('success', Yii::t('forum', 'Thank You. Your topic created.'));
				$this->redirect('index');
			}
		}
		
		// Add page breadcrumb and title
		$this->pageTitle = Yii::t('forum', 'Create A Topic');
		$this->breadcrumbs = array(
			array('Forum', array('index')),
			array($forum->name, array()),
			array(Yii::t('forum', 'Create A Topic'), array())			
		);
		
		
		// Render
		$this->render('add_topic', array('model'=>$model));
	}
	
	/**
	 * View Topic Action
	 */
	public function actionviewtopic()
	{
		if(!isset($_GET['topic']))
			throw new CHttpException(400, 'no forum name/id was given.');
		
		$topic = ForumTopic::model()->findByPk(isset($_GET['topicid']) ? $_GET['topicid'] : $_GET['topic']);
		
		if(!$topic && ($topic = ForumTopic::model()->findByAttributes(array('name' => $_GET['topic']))) === null)
		{
			throw new CHttpException(404, Yii::t('forum', 'Could not find that topic, did you enter the correct name/id ?'));
		}
		
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

		$count = ForumMessage::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::POST_PAGE_SIZE;
		#$pages->route = '/as/forum/topic/'.$topic->id . '-' . $topic->alias;
		#$pages->params = array('lang'=>false);

		$pages->applyLimit($criteria);

		$posts = ForumMessage::model()->byDateAsc()->with(array('user'))->findAll($criteria);
			
		// Show titles and nav
		$this->pageTitle = Yii::t('forum', 'viewing topic: {title}', array('{title}'=> $topic->name ));
		$this->breadcrumbs = array(
			array('Forum', array('index')),
			array($topic->forum->name, array()),
			array(Yii::t('forum', 'viewing topic: {title}', array('{title}'=> $topic->name)), array())
		);

		$markdown = new CMarkdownParser;
		
		# Are we subscribed into this topic?
		# $subscribed = TopicSubs::model()->find('topicid=:topicid AND userid=:userid', array( ':topicid' => $topic->id, ':userid' => Yii::app()->user->id ) );
			
		#Render
		$this->render('view_topic', array( /*'subscribed' => $subscribed,*/ 'markdown' => $markdown, 'model' => $topic, 'posts' => $posts, 'newPost' => $newPost, 'count' => $count, 'pages' => $pages, 'can' => $access ));
	}
}
