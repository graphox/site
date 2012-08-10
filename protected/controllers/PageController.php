<?php

class PageController extends Controller
{
    /**
     * @var string the default layout for the views.
     */
    public $layout='//layouts/column2';

	/**
	 * Displays an index page
	 */
	public function actionIndex()
	{
		$model = Entity::model()->findAllTypeWithAccess('object', 'page');
		
		$this->render('index', array(
			'dataProvider' => new CArrayDataProvider($model)
		));
	}
	
	/**
	 * Creates a page.
	 */	
	public function actionCreate()
	{
		$model = new PageEntity;
		
		$this->performAjaxValidation($model);

        if(isset($_POST['PageEntity']))
        {
            $model->attributes = $_POST['PageEntity'];
            
            if($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
	}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
    	$this->render('view',array(
            'model'=>$this->loadModel($id)->typeModel,
        ));
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id)->typeModel;

		if($model->can('update') !== TRUE)
			throw new CHttpException(403, 'access denied.');
		
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if(isset($_POST['PageEntity']))
        {
            $model->attributes=$_POST['PageEntity'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
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
            $model = $this->loadModel($id);
			if($model->typeModel->can('delete') !== TRUE)
				throw new CHttpException(403, 'access denied.');
			
			$model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=Entity::model()->findByPk($id);
		
		if($model === null or $model->subtype_id !== EntityType::model()->findByAttributes(array('name' => 'blog'))->id)
			throw new CHttpException(404,'The requested blog post does not exist.');
        
		return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='entity-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}