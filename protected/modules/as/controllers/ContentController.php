<?php

class ContentController extends AsController
{
	/**
	 * @var object the content object used by the actions and the contextfilter
	 */
	protected $_content;
	
	/**
	 * @var object the actions the user is allowed to perform
	 */	
	protected $_can;
	
	/**
	 * @var int the type id given in the uri
	 */
	protected $_type;
	protected $_type_id;

	/**
	 * @var string the default layout for the views.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'as.components.AsAccessControlFilter + create',
			),
			'guest - index, view',
			'type - admin, create',
			'typeLight + create',
			'context - index, admin, create',
		);
	}
	
	/**
	 * Make shure the user is not a guest
	 */
	public function filterGuest($filterChain)
	{
		if(Yii::app()->user->isGuest)
			$this->denyAccesS();
		else
			$filterChain->run();
	}
	
	/**
	 * Filter type when available
	 */
	
	public function filterTypeLight($filterChain)
	{
		if(isset($_GET['type']))
		{
			$this->_type = ContentType::model()->findByAttributes(array(
				'name' => $_GET['type']
			));
		
			if($this->_type === null)
				throw new CHttpException(404, 'Invalid type');
			
			$this->_type_id = $this->_type->id;
		}
		
		return $filterChain->run();	
	}
	
	/**
	 * Filter the Type
	 */
	public function filterType($filterChain)
	{
		if(!isset($_GET['type']))
			$this->redirect(array('//as/content', 'type' => 'blog'));
		
		$this->_type = ContentType::model()->findByAttributes(array(
			'name' => $_GET['type']
		));
		
		if($this->_type === null)
			throw new CHttpException(404, 'Invalid type');
			
		$this->_type_id = $this->_type->id;
		
		return $filterChain->run();
	}

	/**
	 * Filters the context
	 */
	public function filterContext($filterChain)
	{
		$criteria = new CDBCriteria;
		
		if(!isset($_GET['id']) && !isset($_GET['name']))
			$this->redirect(array('//as/content'));
		
		if(isset($_GET['id']))
			$criteria->compare('id', $_GET['id']);
		
		if(isset($_GET['name']))
			$criteria->compare('name', $_GET['name']);
		
		$criteria->compare('type_id', $this->_type_id);
	
		$this->_content = Content::model()->find($criteria);
		
		if($this->_content === null)
			throw new CHttpException(404, 'Could not find content');
	
		#force urls like content/id/name/<action>
		if(!isset($_GET['id']) || !isset($_GET['name']))
			$this->redirect(array('//as/content/'.$this->action->id, 'id' => $this->_content->id, 'name' => $this->_content->name));
		
		$this->_can = Yii::app()->accessControl->getAccess($this->_content->aclObject, array(
			'read' => true,
			'edit' => false,
			'delete' => false,
			'comment' => true,
			'like' => true,
			'addAttachment' => false,
		));
		
		if(!$this->_can->read)
			$this->denyAccess();
		
		//run next filter
		return $filterChain->run();
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		if(isset($_GET['download']))
			$this->_content->download();
		else
			$this->render('view',array(
				'model'=>$this->_content,
				'can' => $this->_can
			));
	}

	/**
	 * Creates a new model.
	 */
	public function actionCreate()
	{
		$model=new Content;
		$this->performAjaxValidation($model, 'content-form');

		if(isset($_POST[get_class($model)]))
		{
			$model->attributes=$_POST[get_class($model)];
			
			if($model->save())
				Yii::app()->user->setFlash('content.success', 'Successfully edited content. '.CHtml::link('click here to go to the page', array('//as/content/view', 'id' => $model->id, 'name' => $model->name)));
		}
		elseif(isset($this->_type_id))
			$model->type_id = $this->_type_id;

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
		if(!$this->_can->edit)
			$this->denyAccess();
	
		$model=$this->_content;
		$this->performAjaxValidation($model, 'content-form');

		if(isset($_POST[get_class($model)]))
		{
			$model->attributes=$_POST[get_class($model)];
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
			if(!$this->_can->delete)
				$this->denyAccess();
		
			// we only allow deletion via POST request
			$model=$this->_content;

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
		$criteria = new CDbCriteria(array(
			'condition' => '(published = 1 OR creator_id = :user_id OR updater_id = :user_id) AND type_id = :type_id',
			'params' => array(
				':type_id' => (int)$this->_type_id,
				':user_id' => Yii::app()->user->isGuest ? -1 : (int)Yii::app()->user->id
			),
			'order'=>'created_date DESC',
			'with'=>array('aclObject'),
		));
		

		
		$models = Content::model()->findAllWithAccess(array('read' => true));
		$criteria->addInCondition('t.id', AsActiveRecord::format($models, 'i', 'id'));
	
		$models = new CActiveDataProvider('Content', array(
			'criteria'=> $criteria,
			'pagination'=>array(
				'pageSize' => 20,
			),
		));

		$this->render('index',array(
			'models' => $models
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Content('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Content']))
			$model->attributes=$_GET['Content'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
