<?php

class PageController extends Controller
{
	function actionIndex()
	{
		if(isset($_GET['id']))
			$page = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'id' => $_GET['id']));
		elseif(isset($_GET['name']))
			$page = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'title' => $_GET['name']));
		else
			throw new CHttpException(400, 'no id or title given');
	
		if(!$page)
			throw new CHttpException(404, 'Could not find requested page');
				
		else
		{
			try
			{
					
				$can = AccessControl::GetUserAccess($page->aclObject);
				
				if(!$can->read)
					$this->denyAccess();
						
				$this->breadcrumbs[] = array($page->title, array($page->uri), array('class' => 'active'));
						
				$parent = $page;
						
				while($parent = $parent->parent)
					$this->breadcrumbs[] = array($parent->title, array($parent->uri));
						
						
				array_reverse($this->breadcrumbs);
						
				if(isset($page) && !empty($page->layout))
					$this->layout = $page->layout;
				
				$sidebar = array();
				
				foreach($page->pages as $sub)
					if($sub->module == 'web')
						#TODO: config var
						if(true)
							$sidebar[] = array('text' => $sub->title, 'url' => array('/'.$sub->uri));
						else
							$sidebar[] = array('text' => $sub->title, 'url' => array('//as/page/', 'name' => $sub->title, 'id' => $sub->id));
				
				$sidebar = array(
					array(
						'title' => 'Children',
						'sub' => $sidebar
					)
				);
						
				$this->render('as.views.page.page', array('can' => $can, 'page' => $page, 'show_meta' => true, 'sidebar' => $sidebar));
						
						
			}
			catch(Exception $e)
			{
				if($e->getMessage() == 'Could not find rule' || $e->getMessage() == 'Could not find object priviliges' || $e->getMessage() == 'Access not found, Could not find user from id')
					$this->denyAccess();
				else
					throw $e;
			}
		}
	}

}
