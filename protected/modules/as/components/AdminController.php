<?php

class AdminController extends AsController
{
	public $layout='//layouts/admin';
	
	public $breadcrumbs = array(
		array('admin', array('admin/index'))
	);
	
	public $menu = array(
		array('General',
			array(
				array('Pages', array('//as/admin/pages'), 'categories'),
				array('Images', array('//as/admin/images'), 'photo'),
				array('Comments', array('//as/admin/comments'), 'tags'),
				array('Votes', array('//as/admin/votes'), 'jump_back'),
				array('Menu', array('//as/admin/menu'), 'categories'),
			)
		),
		
		array('Accesscontrol',
			array(
				array('groups', array('//as/admin/acl/groups'), 'view_users'),
				array('Groups - Users', array('//as/admin/acl/groupUser'), 'profile'),
				array('Privileges', array('//as/admin/acl/privileges'), 'security'),
				array('Objects', array('//as/admin/acl/object'), 'folder'),
			)
		),

		array('Users',
			array(
				array('useraccounts', array('//as/admin/user/accounts'), 'view_users'),
				array('profiles', array('//as/admin/user/profile'), 'profile'),
				array('user groups', array('//as/admin/acl/groupUser'), 'security'),
			)
		),

		array('Clans',
			array(
				array('clans', array('//as/admin/clans/clans'), 'view_users'),
				array('members', array('//as/admin/clans/members'), 'profile'),
				array('ranks', array('//as/admin/clans/ranks'), 'security'),
				array('tags', array('//as/admin/clans/tags'), 'categories'),
			)
		),

		array('pm',
			array(
				array('messages', array('//as/admin/pm/messages'), 'video'),
				array('directories', array('//as/admin/pm/directories'), 'folder'),
			)
		),
				
		array('admin',
			array(
				array('logout', array('//as/auth/logout'), 'jump_back')
			)
		)
	);
	
	public function render($view, $data = null, $return = false)
	{
		$new_crumbs = array();
		
		$pre = '//as';
		foreach(explode('/', $this->getId()) as $element)
		{
			$pre .= '/'.$element;
			$new_crumbs[] = array($element, array($pre));
		}
		
		foreach($this->breadcrumbs as $crumb)
			if(isset($crumb[1]) && isset($crumb[1][0]) && 'index' == $crumb[1][0])
				continue;
			else
				$new_crumbs[] = $crumb;

		$this->breadcrumbs = $new_crumbs;
		
		parent::render($view, $data, $return);
	}

}
