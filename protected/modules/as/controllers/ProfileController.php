<?php

class ProfileController extends Controller
{
	function actionIndex()
	{
		if(!isset($_GET['name']))
			throw new CHttpException(400, 'no name given');
		
		$user = User::model()->with('profile', 'names')->findByAttributes(array('username' => $_GET['name']));
		
		$this->render('view', array('user' => $user));
	}

}
