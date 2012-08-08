<?php

class PageController extends Controller
{
    /**
     * @var string the default layout for the views.
     */
    public $layout='//layouts/column2';

	public function actionIndex()
	{
		$model = Entity::model()->findAllTypeWithAccess('object', 'page');
		
		$this->render('index', array(
			'dataProvider' => new CArrayDataProvider($model)
		));
	}
	
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
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Entity']))
        {
            $model->attributes=$_POST['Entity'];
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
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }
    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Entity('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Entity']))
            $model->attributes=$_GET['Entity'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=Entity::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='entity-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
