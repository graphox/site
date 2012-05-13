<?php
class ClansController extends AdminController
{

	public $layout='//layouts/admin';

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$this->breadcrumbs = array(
			array('Clans', array('index')),
			array('Overview', '', array('class' => 'active'))
		);
		
		if((($access = AccessControl::GetAccess('Clans::Overview')) === false) || $access->read != 1)
			$this->denyAccess();
		
		//TODO: search
		$criteria = new CDbCriteria();
		$criteria->select = '*';

		if(isset($_POST['search']) && is_array($_POST['search']))
		{
				if(isset($_POST['search']['id']))
					$criteria->compare('id', $_POST['search']['id']);
				if(isset($_POST['search']['name']))
					$criteria->compare('name', $_POST['search']['name']);
				if(isset($_POST['search']['description']))
					$criteria->compare('description', $_POST['search']['description']);
				if(isset($_POST['search']['acl_group_id']))
					$criteria->compare('acl_group_id', $_POST['search']['acl_group_id']);
				if(isset($_POST['search']['status']))
					$criteria->compare('status', $_POST['search']['status']);
				if(isset($_POST['search']['page_id']))
					$criteria->compare('page_id', $_POST['search']['page_id']);
				if(isset($_POST['search']['forum_id']))
					$criteria->compare('forum_id', $_POST['search']['forum_id']);
		}
		else
			$criteria->condition = '1';


		if(isset($_POST['inspect']))
			$this->redirect(array('inspect', 'id' => $_POST['inspect']));

		if(isset($_POST['trash']))
			$this->redirect(array('delete', 'id' => $_POST['trash']));
		
		if(isset($_POST['edit']))
			$this->redirect(array('edit', 'id' => $_POST['edit']));

		
		$count=Clans::model()->count($criteria);
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize = isset($_GET['per-page']) ? (int)$_GET['per-page'] : 10;
		$pages->applyLimit($criteria);
		$models = Clans::model()->findAll($criteria);
		
		$this->render('_main_view',array(
			'models' => $models,
			'pages' => $pages,
			'form_model' => new Clans,
			'can' => $access
		));
	}
	
	public function actionInspect()
	{
		if((($access = AccessControl::GetAccess('Clans::Overview')) === false) || $access->read != 1)
			$this->denyAccess();

		$this->breadcrumbs = array(
			array('Clans', array('index')),
			array('Inspect', '', array('class' => 'active'))
		);
	
		$model = $this->loadModel($_GET['id']);
		
		$this->render('_module' , array('_partial_' => '_inspect', 'model' => $model));
	}

	public function actionDelete()
	{
		if((($access = AccessControl::GetAccess('Clans::Overview')) === false) || $access->delete != 1)
			$this->denyAccess();

		$this->breadcrumbs = array(
			array('Clans', array('index')),
			array('Delete', '', array('class' => 'active'))
		);
		
		if($this->loadModel($_GET['id'])->delete() === false)
			throw new exception('Could not delete');
		
		Yii::app()->user->setFlash('success', 'Successfully removed #'.(int)$_GET['id']);
		$this->redirect(array('index'));
	}
	
	public function actionAdd()
	{
		if((($access = AccessControl::GetAccess('Clans::Overview')) === false) || $access->write != 1)
			$this->denyAccess();

		$this->breadcrumbs = array(
			array('Clans', array('index')),
			array('Add', '', array('class' => 'active'))
		);

		$model=new Clans;

		// comment the following line if AJAX validation is unneeded
		$this->performAjaxValidation($model);

		if(isset($_POST['Clans']))
		{
			$model->attributes=$_POST['Clans'];
			if($model->save())
				$this->redirect(array('inspect','id'=>$model->id));
		}
		
		$this->render('_module' , array('_partial_' => '_add_form', 'form_model' => $model));
		
	}

	public function actionEdit()
	{
		if((($access = AccessControl::GetAccess('Clans::Overview')) === false) || $access->update != 1)
			$this->denyAccess();

		$this->breadcrumbs = array(
			array('Clans', array('index')),
			array('Edit', ''),
			array((int)$_GET['id'], array('', 'id' => (int)$_GET['id']), array('class' => 'active'))
		);
		
		$model = $this->loadModel($_GET['id']);

		// comment the following line if AJAX validation is unneeded
		$this->performAjaxValidation($model);

		if(isset($_POST['Clans']))
		{
			$model->attributes=$_POST['Clans'];
			if($model->save())
				$this->redirect(array('inspect','id'=>$model->id));
		}
	
		$this->render('_module' , array( 'action' => array(), '_partial_' => '_add_form', 'form_model' => $model));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Clans::model()->findByPk($id);
		
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='clans-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
