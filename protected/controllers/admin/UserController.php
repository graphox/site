<?php

class UserController extends AdminController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
		#	'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'updateEmail', 'changeStatus'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'deleteEmail'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;
		$model->scenario = 'admin';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success', '<strong>User created!</strong> Successfully added user, you may now add email addresses and/or metadata.');
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id, array('emails', 'entity' => array( 'with' => array('metadata') )));
		$model->scenario = 'admin';
		$this->performAjaxValidation($model);

		$email_model = new Email('admin');
		if(isset($_POST['Email']))
		{
			$email_model->attributes = $_POST['Email'];
			if($email_model->validate())
			{
				$email_model->user_id = (int)$id;
				if($email_model->save())
				{
					#reset
					$email_model = null;
					$email_model = new Email('admin');
					Yii::app()->user->setFlash('success', '<strong>Email saved!</strong> the email address was saved in the database.');
				}
			}
		}

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if(!isset($_POST['Email']) && $model->save())
			{
				Yii::app()->user->setFlash('success', '<strong>User updated!</strong> Successfully updated user settings.');
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'email_model' => $email_model
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 *	Deletes an email adress
	 */
	public function actionDeleteEmail($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$email = Email::model()->findByPk($id);
	
			if($email === null)
				throw new CHttpException(404,'Could not find that email address.');
				
			$user_id = $email->user_id;
			$email->delete();

			if(!Yii::app()->request->isAjaxRequest)
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('update', 'id' => $user_id));
			else
				echo 'Successfully deleted email address.';
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Updates an email address
	 */
	
	public function actionUpdateEmail($id)
	{
		$model = Email::model()->findByPk($id);
		$model->scenario = 'admin';
	
		if($model === null)
			throw new CHttpException(404,'Could not find that email address.');

		if(isset($_POST['ajax']) && $_POST['ajax']==='email-update-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}	

		if(isset($_POST['Email']))
		{
			$model->attributes = $_POST['Email'];
			
			if($model->save())
			{
				if(!Yii::app()->request->isAjaxRequest)
					$this->redirect(array('view', 'id'=>$model->user_id, '#' => 'email-'.(int)$model->id));
				else
				{
					echo '<p>note, refresh the page to see the changed data</p>';
					echo '<p>Successfully saved email</p>';
				}
				
				Yii::app()->end();
			}
		}
		
		$form = new CActiveForm;
		echo $form->errorSumarry($model);
	}

	/**
	 *	Changes the user status
	 */
	public function actionChangeStatus($id)
	{
		$model = $this->loadModel($id);
		$model->scenario = 'statusUpdate';
		
		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			
			if($model->save() && $model->sendStatusMail())
			{
				Yii::app()->user->setFlash('success', 'Successfully updated user status!');
				$this->redirect(array('update', 'id' => $id, '#' => '/status'));
			}
		}

		Yii::app()->user->setFlash('error', 'Could not update user status!');
		$this->redirect(array('update', 'id' => $id, '#' => '/status'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $with = array())
	{
		$model=User::model()->with($with)->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
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
