<?php

class PageController extends Controller
{
	function actionEdit()
	{
		if(isset($_GET['id']))
			$model = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'id' => $_GET['id']));
		elseif(isset($_GET['name']))
			$model = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'title' => $_GET['name']));		
		else
			$model = new Pages;
		
		$model->loadDefaults();
		
		if(!$model)
			throw new CHttpException(404, 'could not find page to edit');
		
		# New page
		if(!$model->id || !$model->aclObject)
		{
			$acl_object = AccessControl::GetObjectByName($model->module.'.pages');
		
			if(!$acl_object)
				$acl_object = AccessControl::AddObject($model->module.'.pages');
		}
		else
			$acl_object = $model->aclObject;
		
		$access = AccessControl::GetUserAccess($acl_object);

		if($access->read != 1  || $access->update != 1)
			$this->denyAccess();

		
		if(isset($_POST['Pages']))
		{
			$model->attributes = $_POST['Pages'];
			
			$model->loadDefaults();
			
			if($model->validate())
			{
				if(!in_array(ContentMakeup::UserAllowed(), $model->markup))
					$model->addError('markup', 'You\'ve selected an invalid type.');
				elseif($model->save())
				{
					Yii::app()->user->setFlash('edit-page', 'Successfully saved page!');
					Yii::app()->user->setFlash('edit-page:id', $model->id);
				}
			}
		}
		
		if($model->parent == null)
			$model->no_parent = 1;
		
		$this->render('edit', array('model' => $model));
	}
	
	function actionEditPreview()
	{
		if(isset($_POST['data']))
		{
			$parser = new CMarkdownParser;
			$parsedText = $parser->safeTransform($_POST['data']);
			echo $parsedText;
		}
	}
	
	function actionIndex()
	{
	
		if(isset($_GET['id']))
			$page = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'id' => $_GET['id']));
		elseif(isset($_GET['name']))
			$page = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'title' => $_GET['name']));
		else
			$page = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'uri' => '/'));
	
		if(!$page)
			throw new CHttpException(404, 'Could not find requested page');
		
		else
		{
			#SEO
			if(!isset($_GET['name']) || !isset($_GET['id']) || $_GET['name'] != $page->title)
				$this->redirect(array('//as/page/', 'id' => $page->id, 'name' => $page->title, /* prevent reset on pagination: 'page' => isset($_GET['page']) ? $_GET['page'] : 0*/));
		
			$can = AccessControl::GetUserAccess($page->aclObject);
				
			if($can->read != 1)
				$this->denyAccess();
						
				$this->breadcrumbs[$page->title] = array('//as/page', 'id' => $page->id, 'name' => $page->title);
						
				$parent = $page;
						
				while($parent = $parent->parent)
					$this->breadcrumbs[$page->title] = array('//as/page', 'id' => $parent->id, 'name' => $parent->title);
						
						
				array_reverse($this->breadcrumbs);
						
				//if(isset($page) && !empty($page->layout))
				//	$this->layout = $page->layout;
				
				$sidebar = array();
				
				foreach($page->pages as $sub)
					if($sub->module == 'web')
						#TODO: config var
						//if(true)
						//	$sidebar[] = array('text' => $sub->title, 'url' => array('/'.$sub->uri));
						//else
							$sidebar[] = array('text' => $sub->title, 'url' => array('//as/page/', 'name' => $sub->title, 'id' => $sub->id));
				
				$sidebar = array(
					array(
						'title' => 'Children',
						'sub' => $sidebar
					)
				);
						
				$this->render('page', array('can' => $can, 'page' => $page, 'show_meta' => true, 'sidebar' => $sidebar));
						
		}
	}
	
	function actionComment()
	{
		if(!isset($_GET['action']))
			$this->missingAction($this->getAction()->id);
		
		switch($_GET['action'])
		{
			case 'reply':
				$this->commentReplyAction();
				break;
			case 'add':
				$this->commentEditAction(false);
				break;
				
			case 'edit':
				$this->commentEditAction();
				break;
		
			default:
				$this->missingAction($this->getAction()->id.'/action/'.$_GET['action']);
		}
		
	}
	
	public function commentEditAction($editing = true)
	{
		if($editing && !isset($_GET['id']))
			throw new CHttpException(400, 'Comment id missing');
		elseif($editing)
			$model = PageComments::model()->with('page', 'page.aclObject')->findByPk($_GET['id']);
		else
			$model = new PageComments;
		
		if(!$model)
			throw new CHttpException(404, 'could not find comment.');
		
		# New page
		if(!$editing)
		{
			if(!isset($_GET['page']))
				throw new CHttpException(400, 'Page missing.');
			
			$page = Pages::model()->findByPk($_GET['page']);
			
			if(!$page)
				throw new CHttpException(404, 'Invalid page id.');
			
			$model->page_id = $page->id;
		}
		else
			$page = $model->page;
		
		if(!$page)
			throw new CHttpException(404, 'Invalid page id');
		
		$access = AccessControl::GetUserAccess($page->aclObject);

		if($access->read != 1  || ($editing && $access->update != 1 || $access->write != 1))
			$this->denyAccess();

		if(isset($_POST['PageComments']))
		{
			$model->attributes = $_POST['PageComments'];
			
			$model->posted_date = new CDbExpression('NOW()');
						
			if($model->validate())
				if(!in_array(ContentMakeup::UserAllowed(), $model->markup))
					$model->addError('markup', 'You\'ve selected an invalid type.');
				elseif($model->save())
				{
					Yii::app()->user->setFlash('edit-comment', 'Successfully saved page!');
					Yii::app()->user->setFlash('edit-comment:uri', $this->createUrl('//as/page', array('id' => $model->page->id, 'title' => $model->page->title, '#' => 'comment-'.$model->id)));
				}
		}
		
		$this->render('edit_comment', array('model' => $model, 'action' => array('//as/page/comment', 'action' => $editing ? 'edit' : 'add', 'id' => $editing ? $model->id: null, 'page' => $editing ? null : $_GET['page'])));
	}
	
	public function commentReplyAction()
	{
		if(!isset($_GET['id']))
			throw new CHttpException(400, 'Comment id missing');
		
		$model = PageComments::model()->with('page', 'page.aclObject')->findByPk($_GET['id']);

		if(!$model)
			throw new CHttpException(404, 'could not find comment.');
		
		$form_model = new PageComments;
		$form_model->content = '<q>'.$model->content.'</q>';
		
		$this->render('edit_comment', array('model' => $form_model, 'action' => array('//as/page/comment', 'action' => 'add', 'page' => $model->page_id)));
	}
}
