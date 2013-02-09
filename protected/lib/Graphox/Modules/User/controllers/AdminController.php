<?php

class AdminController extends AsAdminController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
			'simpleAccess'
		);
	}
	
	public function filterSimpleAccess($chain)
	{
		if(!Yii::app()->user->isGuest && Yii::app()->user->isAdmin)
			$chain->run();
		else
		{
			if(Yii::app()->user->isGuest)
				$this->redirect(array('/user/login'));
			throw new CHttpException(403, 'Only admins can access the ACP.');
		}
			
	}
	/**
	 * Displays a particular model.
	 * @param integer $name the username of the model to be displayed
	 */
	public function actionView($name)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($name),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $name the username of the user to be updated
	 */
	public function actionUpdate($name)
	{
		$model=$this->loadModel($name);
		
		$model->scenario = 'admin';
		$this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->update())
			{
				Yii::app()->user->setFlash('success', '<strong>User updated!</strong> Successfully updated user settings.');
				$this->redirect(array('view','name'=>$model->username));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actions()
	{
		return array(
			'delete' => array(
				'class' => 'application.components.ActionVerifyUserAction',
				'onRun' => function(&$it)
				{
					$it->controller->loadModel($_GET['name'])->delete();
				},
				'shortDescr' => 'Are you shure you want to delete the user?',
				'longDescr' => 'deleting is permanent. All posts will loose the creator. Perhaps you ment banning the user?',
				'returnUrl' => array('/admin/user'),
		
			),

			'activate' => array(
				'class' => 'application.components.ActionVerifyUserAction',
				'onRun' => function(&$it)
				{
					$it->controller->loadModel($_GET['name'])->actionAdminActivate();
				},
				'shortDescr' => 'Are you shure you want to activate the user?',
				'longDescr' => 'this will enable the user to login when he activate(s)(d) his email adress too.',
				'returnUrl' => array('/admin/user'),
			),

			'emailActivate' => array(
				'class' => 'application.components.ActionVerifyUserAction',
				'onRun' => function(&$it)
				{
					$it->controller->loadModel($_GET['name'])->actionEmailActivated();
				},
				'shortDescr' => 'Are you shure you want to <strong>email</strong> activate the user?',
				'longDescr' => 'The user won\'t have to activate it\'s email address anymore!',
				'returnUrl' => array('/admin/user'),
			),
						
			'ban' => array(
				'class' => 'application.components.ActionVerifyUserAction',
				'onRun' => function(&$it)
				{
					$it->controller->loadModel($_GET['name'])->actionBan();
				},
				'shortDescr' => 'Are you shure you want to ban the user?',
				'longDescr' => 'The user won\'t be able to login anymore.',
				'returnUrl' => array('/admin/user'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CArrayDataProvider(
				User::model()->findAll()
		);
		
		$inactiveDataProvider = new CArrayDataProvider(
				User::model()->findAllByAttributes(array(
					'isAdminActivated' => false
				))
		);
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'notActivatedProvider' => $inactiveDataProvider
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string name the username of the user to load
	 * @return User
	 */
	public function loadModel($name)
	{
		$model=User::model()->findByAttributes(array('username' => $name));
		
		if($model===null)
			throw new CHttpException(404,'Invalid user.');
		
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
