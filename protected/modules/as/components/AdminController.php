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
		
		array('admin',
			array(
				array('logout', array('//as/auth/logout'), 'jump_back')
			)
		)
	);

}
