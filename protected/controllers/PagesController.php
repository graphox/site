<?php

class PagesController extends CController
{
	function actionIndex()
	{
#		$builder = Yii::app()->db->createCommand();
#		$builder->select('*');
#		$builder->from('users');
#		$result = $builder->query();
#		print_r($result->readAll());
		$rows = ForumTopics::model()->findAll();
		
		$first = true;
		foreach($rows as $topic)
		{
			print_r($topic);
			
			if($first)
			{
				$topic->title = uniqid();
				$topic->save();
			}
			$first = false;
			
		}
		Yii::app()->end();
	}	
	
	function actionPage()
	{
		print_r(Yii::app()->request);
	}
	
	function actionAddPage()
	{
		$model = new PageForm('add');
		if(isset($_POST['PageForm']))
		{
			$model->attributes = $_POST['PageForm'];
			
			if($model->validate())
			{
				$id = $model->save();
				$this->redirect(array('page', 'id' => $id), true);
			}
		}
		
		$this->render('//pageadd', array('model' => $model));
	}
}
