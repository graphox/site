<?php

#add if not yet existing
$obj = AccessControl::GetObjectByName($this->modelClass.'::Overview');

$label = $this->pluralize(
	$this->class2name(
		$this->modelClass
	)
);

if(!$obj)
	$obj = AccessControl::AddObject($this->modelClass.'::Overview');

AccessControl::giveAccess($obj);

?><?='<?php'?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?>

{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$this->breadcrumbs = array(
			array('<?=$label?>', array('index')),
			array('Overview', '', array('class' => 'active'))
		);
		
		if(Yii::app()->user->isGuest || ($access = AccessControl::GetAccess('<?=$this->modelClass?>::Overview')) === false)
			throw new CHttpException(403, 'Access denied');
		
		//TODO: search
		$criteria = new CDbCriteria();
		$criteria->select = '*';
		$criteria->condition = '1';

		if(isset($_POST['inspect']))
			$this->redirect(array('inspect', 'id' => $_POST['inspect']));

		if(isset($_POST['trash']))
			$this->redirect(array('delete', 'id' => $_POST['trash']));
		
		if(isset($_POST['edit']))
			$this->redirect(array('edit', 'id' => $_POST['edit']));

		
		$count=<?php echo $this->modelClass; ?>::model()->count($criteria);
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize = isset($_GET['per-page']) ? (int)$_GET['per-page'] : 10;
		$pages->applyLimit($criteria);
		$models = <?php echo $this->modelClass; ?>::model()->findAll($criteria);
		
		$this->render('_main_view',array(
			'models' => $models,
			'pages' => $pages,
			'form_model' => new <?=$this->modelClass?>,
			'can' => $access
		));
	}
	
	public function actionInspect()
	{
		$this->breadcrumbs = array(
			array('<?=$label?>', array('index')),
			array('Inspect', '', array('class' => 'active'))
		);
	
		$model = $this->loadModel($_GET['id']);
		
		$this->render('_module' , array('_partial_' => '_inspect', 'model' => $model));
	}

	public function actionDelete()
	{

		$this->breadcrumbs = array(
			array('<?=$label?>', array('index')),
			array('Delete', '', array('class' => 'active'))
		);
		
		if($this->loadModel($_GET['id'])->delete() === false)
			throw new exception('Could not delete');
		
		Yii::app()->user->setFlash('success', 'Successfully removed #'.(int)$_GET['id']);
		//$this->redirect(array('index'));
	}
	
	public function actionAdd()
	{

		$this->breadcrumbs = array(
			array('<?=$label?>', array('index')),
			array('Add', '', array('class' => 'active'))
		);

		$model=new <?php echo $this->modelClass; ?>;

		// comment the following line if AJAX validation is unneeded
		$this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if($model->save())
				$this->redirect(array('inspect','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
		}
		
		$this->render('_module' , array('_partial_' => '_add_form', 'form_model' => $model));
		
	}

	public function actionEdit()
	{
		$this->breadcrumbs = array(
			array('<?=$label?>', array('index')),
			array('Edit', ''),
			array((int)$_GET['id'], array('', 'id' => (int)$_GET['id']), array('class' => 'active'))
		);
	
		$this->render('_module' , array( 'action' => array(), '_partial_' => '_add_form', 'form_model' => $this->loadModel($_GET['id'])));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
		
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo $this->class2id($this->modelClass); ?>-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
