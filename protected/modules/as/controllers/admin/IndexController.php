<?php
class IndexController extends AdminController
{

	public $layout='//layouts/admin';

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$this->breadcrumbs = array(
			array('admin', array('index')),
		);
		
		if((($access = AccessControl::GetAccess('AdminObject')) === false) || $access->read != 1)
			$this->denyAccess();
			
		$this->render('as.views.admin.index');
		
	}
}
