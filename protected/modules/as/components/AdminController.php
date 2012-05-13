<?php

class AdminController extends AsController
{
	public $layout='//layouts/admin';
	
	public $breadcrumbs = array(
		array('admin', array('admin/index'))
	);
	
	public $menu = array(
		array('pages',
			array(
				array('overview', array('//as/admin/pages'), 'categories'),
				array('new page', array('//as/admin/pages/add'), 'new_article'),
			)
		),
		
		array('Accesscontrol',
			array(
				array('groups', array('//as/admin/acl/groups'), 'view_users'),
				array('Groups - Users', array('//as/admin/acl/gropUser'), 'profile'),
				array('Privileges', array('//as/admin/acl/privileges'), 'security'),
				array('Objects', array('//as/admin/acl/object'), 'folder'),
			)
		),
				
		array('admin',
			array(
				array('logout', array('//as/auth/logout'), 'jump_back')
			)
		)
	);

}
