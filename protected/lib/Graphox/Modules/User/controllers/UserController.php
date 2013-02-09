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
	
	public function actionProfile()
	{
		$model = null;
		if(!isset($_GET['name']) && !Yii::app()->user->isGuest)
			$model = User::model()->findById(Yii::app()->user->id);
		elseif(isset($_GET['name']))
			$model = User::model()->findByAttributes (array('username' => $_GET['name']));
		
		if($model === null)
			throw new CHttpException(404, 'Could not find profile.');
		
		if(!isset($_GET['subaction']) || Yii::app()->user->isGuest)
			$_GET['subaction'] = 'view';
		
		switch($_GET['subaction'])
		{
			case 'view':
			default:
				$this->render('viewProfile', array('model' => $model));
			break;
		
			case 'edit':
				$this->subActionEditProfile($model);
			break;
		}
	}
	
	public function subActionEditProfile($model)
	{
		/** @var $model User */
		//$model = Yii::app()->user->node;
		if($model->id !== Yii::app()->user->node->id) /** @todo some more access checks here */
			throw new CHttpException(403, 'Access denied!');
		
		$model->scenario = 'profile';
		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			if($model->save())
			{
				Yii::app()->user->setFlash('success', 'successfully saved profile!');
				$this->redirecT(array('/user/profile', 'name' => $model->username));
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
