<?php

class ClanController extends Controller
{
	function actionNew()
	{
		$clan = new Clans;
		
		if(isset($_POST['Clans']))
			$_POST['clans'] = $_POST['Clans'];
		
		if(isset($_POST['clans']))
		{
			$clan->name = isset($_POST['clans']['name']) ? $_POST['clans']['name'] : null;
			$clan->description = isset($_POST['clans']['description']) ? $_POST['clans']['description'] : null;
			$clan->acl_group_id = -1;
			$clan->status = Clans::NOT_ACTIVATED;
		
			if($clan->validate())
			{
				if(($parent = AccessControl::getGroup('clans')) == false)
					$parent = AccessControl::AddGroup('clans');
				
				$clan->acl_group_id = AccessControl::AddGroup('clan_'.$clan->name)->id;
				
				if($clan->save())
				{
					#...
					Yii::app()->user->setFlash('success', 'successfully created a clan, now please wait for an admin to verify the request.');
				}
			}
		}
		
		$this->render('as.views.clan.add', array('model' => $clan));
		
	}

	public function _find($page)
	{
		foreach($page->pages as $page)
		{
			if($page->title == $_GET['page'])
			{
				return $page;
			}
			
			$res = $this->_find($page);
			if($res !== false)
				return $res;
		}
		
		return false;
	}
	
	function actionPage()
	{
		if(!isset($_GET['name']))
			throw new CHttpException(400, 'name not provided');

		if(!isset($_GET['page']))
			throw new CHttpException(400, 'page not provided');

		$clan = Clans::model()->findByAttributes(array('name' => $_GET['name']));
		
		$page = $this->_find($clan->page);
		
		if($page === false)
			throw new CHttpException(404, 'Could not find clan page');


		$sub_pages = array();
		
		foreach($page->pages as $sub)
			$sub_pages[] = array('text' => $sub->title, 'url' => array('//as/clan/page/', 'name' => $clan->name, 'id' => $clan->id, 'page' => $sub->title));
		
	
		$sidebar = array(
			array(
				'title' => 'Sub pages',
				'sub' => $sub_pages				
			),
		
		);

		$this->render('as.views.page.page', array('page' => $page, 'can' => AccessControl::GetUserAccess($page->aclObject), 'sidebar' => $sidebar));
		
	}
	
	function actionView()
	{
		if(isset($_GET['id']))
			$clan = $this->getClan($_GET['id']);
		elseif(isset($_GET['name']))
			$clan = Clans::model()->findByAttributes(array('name' => $_GET['name']));
			
		if(!isset($clan) || $clan == false)
			throw new CHttpException(404, 'could not find clan');
			
		$this->render('as.views.clan.view', array('clan' => $clan));
	}
	
	function actionJoin()
	{
	
	}
	
	function actionManage()
	{
	
	}
	
	function getClan($id)
	{
		return Clans::model()->findByPk($id);
	}
}
