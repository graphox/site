<?php

class PageController extends AdminController
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
		if(!Yii::app()->user->isGuest && Yii::app()->user->node->hasAccess('admin.page'))
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
		$this->performAjaxValidation($model);

		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			if($model->validate() && $model->update())
			{
				Yii::app()->user->setFlash('success', '<strong>Page updated!</strong> Successfully updated page.');
				$this->redirect(array('view','name'=>$model->routeName));
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
				'shortDescr' => 'Are you shure you want to delete this page?',
				'longDescr' => 'It will be gone forever!',
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
				Page::model()->findAll()
		);
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionCreate()
	{
		$model=new Page;
		$this->performAjaxValidation($model);

		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success', '<strong>Page updated!</strong> Successfully updated page.');
				$this->redirect(array('view','name'=>$model->routeName));
			}
		}

		$this->render('create',array(
			'model'=>$model,
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
		$model=Page::model()->findByAttributes(array('routeName' => $name));
		
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
