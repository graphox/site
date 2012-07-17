<?php

class AsAttachmentsWidget extends AsWidget
{
	/**
	 * @var string the name of the model class to use
	 */
	public $modelClass = 'AsAttachmentContent';
	
	/**
	 * @var object the access of the user on the parent object
	 */
	 public $can;
	 
	 /**
	  * @var object the parent object
	  */
	 public $parent;

	 /**
	  * render the form and put the results in the database
	  */
	 public function run()
	 {
	 	$model = new $this->modelClass;
	 	$model->initAttachment($this->parent);

	 	if(!Yii::app()->user->isGuest && $this->can->comment && isset($_POST[$this->modelClass]))
	 	{
	 		$model->attributes = $_POST[$this->modelClass];
			$model->uploadedFile = CUploadedFile::getInstance($model,'uploadedFile');
	 	
	 		if($model->save())
	 		{
				$data = new AttachmentData;
			    $data->name=$model->uploadedFile->name;
			    $data->type=$model->uploadedFile->type;
			    $data->size=$model->uploadedFile->size;
			    $data->content_id = $model->id;
			    $data->data=file_get_contents($model->uploadedFile->tempName);
				    
			    if(!$data->save(false))
			    {
			    	$model->delete();
			    	$model->addError('uploadedFile', 'could not save, plaese try again');
			    }
				else
					Yii::app()->user->setFlash('attachment.success', 'Successfully posted attachment');
	 		}
	 		
	 	}
	 	
		$criteria = new CDbCriteria(array(
			'condition' => '(published = 1 OR creator_id = :user_id OR updater_id = :user_id) AND type_id = :type_id AND parent_id = :parent_id',
			'params' => array(
				':type_id' => (int)$model->type_id,
				':parent_id' => (int)$this->parent->id,
				':user_id' => (int)Yii::app()->user->id,
			),
			'order'=>'created_date DESC',
			'with'=>array('aclObject'),
		));
		
		$criteria->addInCondition('t.id', CHtml::listData(Content::model()->findAllWithAccess(array('read' => true), $criteria), 'id', 'id'));
	
		$models = new CActiveDataProvider('Content', array(
			'criteria'=> $criteria,
			'pagination'=>array(
				'pageSize' => 20,
			),
		));
	 	
	 	$model->renderIndex($models);
	 	$model->renderForm($model, false, array('can' => $this->can));
	 }
	
}
