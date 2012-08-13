<?php

class RatingController extends Controller
{
	public function actionIndex()
	{
		$model = new RatingForm;
		
		if(isset($_POST['RatingForm']))
		{
			$model->attributes = $_POST['RatingForm'];
			if($model->validate())
			{
				if($model->rate())
				{
					Yii::app()->user->setFlash('success', 'successfully added rating!');
					$this->redirect(Yii::app()->request->urlReferrer);
					return;
				}
			}
			else
			{
				if(isset($model->errors['parentId']))
					Yii::app()->user->setFlash('warning', 'Could not rate: <ul><li>'.implode('</li><li>', $model->errors['parentId']).'</li><ul>');
				else
					Yii::app()->user->setFlash('warning', 'Could not rate: <ul><li>'.implode('</li><li>', $model->errors['vote']).'</li><ul>');	
				$this->redirect(Yii::app()->request->urlReferrer);
			}
		}
		throw new CHttpException(400, 'Bad request.');
	}
}
