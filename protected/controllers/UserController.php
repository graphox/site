<?php

class UserController extends Controller
{
	public function actionActivate()
	{
		$model = new ActivationForm;
		
		if(isset($_POST['ActivationForm']))
		{
			$model->attributes = $_POST['ActivationForm'];
			if($model->validate())
			{
				$this->redirect(array('login'));
			}
		}
	
		$this->render('activate', array('model' => $model));
	}

	public function actionIndex()
	{
		if(Yii::app()->user->isGuest)
			$this->redirect(array('login'));
		$this->render('index');
	}

	public function actionLogin()
	{
		if(!Yii::app()->user->isGuest)
			$this->redirect(array('index'));

		$model = new LoginForm;
		
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
			
			if($model->validate() && $model->login())
			{
				Yii::app()->user->setFlash('success', 'successfully logged in.');
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		
		$this->render('login', array('model' => $model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		Yii::app()->user->setFlash('success', 'successfully logged out.');
		$this->redirect(Yii::app()->user->returnUrl);
	}

	public function actionRegister()
	{
		if(!Yii::app()->user->isGuest)
			$this->redirect(array('index'));

		$model = new RegisterForm;
		
		if(isset($_POST['ajax']) && $_POST['ajax'] == 'register-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['RegisterForm']))
		{
			$model->attributes = $_POST['RegisterForm'];

			if($model->save())
			{
				Yii::app()->user->setFlash('success', 'successfully registered! you now should check your inbox for the activation email and wait for an admin to verify too.');
				$this->redirect(array('activate'));
			}
		}

		$this->render('register', array('model' => $model));
	}
	
	public function actionProfile($id)
	{
		$model = Entity::model()->findByPk($id);
		
		if($model === null)
			throw new CHttpException(404, 'Could not find profile.');
		
		$this->render('viewProfile', array('model' => $model->typeModel));
			
	}
	
	public function actionEditProfile()
	{
		$model = Yii::app()->user->entity->typeModel;
		
		if(isset($_POST[get_class($model)]))
		{
			$model->attributes = $_POST[get_class($model)];
			if($model->save())
			{
				Yii::app()->user->setFlash('success', 'successfully saved profile!');
				$this->redirecT(array('/user/profile', 'id' => $model->id, 'name' => $model->name));
			}
		}
		
		$this->render('editProfile', array('model' => $model));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
