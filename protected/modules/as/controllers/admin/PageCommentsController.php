<?php
class PageCommentsController extends Controller
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
		if(Yii::app()->user->isGuest || ($access = AccessControl::GetAccess('PageComments::Overview')) === false)
			throw new CHttpException(403, 'Access denied');
		
		//TODO: search
		$criteria = new CDbCriteria();
		$criteria->select = '*';
		$criteria->condition = '1';
		
		$count=PageComments::model()->count($criteria);
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize = isset($_GET['per-page']) ? (int)$_GET['per-page'] : 10;
		$pages->applyLimit($criteria);
		$models = PageComments::model()->findAll($criteria);
		
		$this->render('_main_view',array(
			'models' => $models,
			'pages' => $pages,
			'form_model' => new PageComments,
			'can' => $access
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=PageComments::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='page-comments-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
