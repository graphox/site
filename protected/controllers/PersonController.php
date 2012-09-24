<?php

class PersonController extends CController
{

	function actionIndex()
	{
		$p = new CActiveDataProvider('Person');
		foreach($p->getData() as $model)
		{
			var_dump($model->id);
			var_dump($model->attributes);
		}
	}
	
	public function actionRegister()
	{
		
	}

}