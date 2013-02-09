<?php

class AsAdminController extends Controller
{
	public $secMenu = array(
		array('label' => 'User management'),
		array('label' => 'create user', 'icon' => 'plus', 'url' => array('/admin/user/create')),
		array('label' => 'manage users', 'icon' => 'briefcase', 'url' => array('/admin/user/admin')),
		
		array('label' => 'entity management'),
		array('label' => 'create entity', 'url' => array('/admin/entity/create')),
		array('label' => 'manage entities', 'url' => array('/admin/entity/admin')),
	);


}
