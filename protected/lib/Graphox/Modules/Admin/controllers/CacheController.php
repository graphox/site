<?php

namespace Graphox\Modules\Admin\Controllers;

use \Yii;
		
class CacheController extends \Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionReset($success = false)
	{
		$form = new \Graphox\Modules\Admin\Forms\ResetCache();
		
		if(isset($_POST[get_class($form)]))
		{
			$form->attributes = $_POST[get_class($form)];
			
			if($form->resetCache())
			{
				//we need a redirect becouse our assets have been deleted
				$this->redirect(array('reset', 'success' => true));
			}
		}
		
		if($success)
		{
			$this->render('success');
			return;
		}
		
		$this->render('reset', array('form' => $form));
	}
}