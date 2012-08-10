<?php

class CommentController extends Controller
{
	public $layout = '//layouts/column2';
	
	public function actionAdd()
	{
		$model = new CommentEntity;
		
		if(isset($_POST['CommentEntity']))
		{
			$model->attributes= $_POST['CommentEntity'];
			
			if($model->save())
			{
				Yii::app()->user->setFlash('success', Yii::t('comment', 'successfully added comment!'));
				$model->source = '';
			}
		}
		
		$this->render('add', array('model' => $model));
	}
	
}
