<?php

class SiteController extends Controller
{
	/**
	 * Redirect custom uris to database pages
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			elseif($error['code'] !== 404)
				$this->render('error', $error);
			else
			{
				$uri = Yii::app()->request->pathInfo; #str_replace(Yii::app()->request->scriptUrl, '', Yii::app()->request->url);
				
				if(!isset($uri[0]) || $uri[0] !== '/')
					$uri = '/'.$uri;

				$page = Pages::model()->with('aclObject')->findByAttributes(array('module' => 'web', 'uri' => $uri/*, 'published' => 1*/));
				
				#really 404
				if(!$page)
					$this->render('error', $error);
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
						{
							$this->breadcrumbs[] = array($parent->title, array($parent->uri));
						}
						
						array_reverse($this->breadcrumbs);
						
						if(isset($page) && !empty($page->layout))
							$this->layout = $page->layout;
						
						$this->render('as.views.page.page', array('can' => $can, 'page' => $page));
						
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
	}

}
