<?php

class GalleryController extends Controller
{
	public $defaultAction = 'gallery';

	public function actionUpload()
	{
		if(Yii::app()->user->isGuest)
		{
			echo json_encode(array('error' => 'You should be logged in to upload a image'));
			return;
		}
			
		Yii::import("ext.EAjaxUpload.qqFileUploader");
		$upload_path = YiiBase::getPathOfAlias('application.data.uploads').'/';
		
		#make shure the directory exists and is writable
		@mkdir($upload_path);
		
		#Todo: add more?
		$allowed_extensions = array("jpg","jpeg","gif","png");
		
		#size limit in bytes
		$size_limit = 10 * 1024 * 1024;
		
		$uploader = new qqFileUploader($allowed_extensions, $size_limit);
		$result = $uploader->handleUpload($upload_path);
		
		$return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		
		if($result['success'])
		{
			$fileSize = filesize($upload_path.$result['filename']);//GETTING FILE SIZE
			$fileName = $result['filename'];//GETTING FILE NAME
			
			$model = new Images;
			$model->name = 'Unnamed';
			$model->description = '';
			$model->owned_by = Yii::app()->user->id;
			$model->added_date = new CDbExpression('NOW()');
			
			if(!$model->save(false))
			{
				$result['success'] = false;
				$result['error'] = 'Could not save';
				
				//clean up the file
				@unlink($result['filename']);
			}
			else
			{
				$result['fileId'] = $model->id;
				rename($upload_path.$result['filename'], $upload_path.$result['fileId']);
			}
			
			$return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		}
 
		echo $return;// it's array
	}
	
	public function actionNew()
	{
		$model = new GalleryForm;
		
		if(isset($_POST['GalleryForm']))
		{
			$model->attributes = $_POST['GalleryForm'];
			
			if($model->save())
				$this->redirect(array('//as/gallery/', 'id' => $model->id));
			
		}
		$this->layout = '//layouts/column2';
		$this->render('new', array('model' => $model));
	}
	
	public function actionEdit()
	{
		if(!isset($_GET['id']))
			throw new CHttpException(404, 'Could not find id');
			
		$model = GalleryForm::model()->findByPk($_GET['id']);

		if(!$model)
			throw new CHttpException(404, 'Could not find by id');
		
		if(isset($_POST['GalleryForm']))
		{
			$model->attributes = $_POST['GalleryForm'];
			if($model->save())
				$this->redirect(array('//as/gallery/', 'id' => $model->id));
		}
		else
			$model->load();
		$this->layout = '//layouts/column2';
		$this->render('new', array('model' => $model, 'action' => array('//as/gallery/edit', 'id' => $_GET['id'])));
	}
	
	public function actionGallery()
	{
		if(!isset($_GET['id']))
		{
			#overview
			$this->actionBrowse();
			return;
		}
		else
		{
			$gallery = Gallery::model()->findByPk($_GET['id']);
			
			if(!$gallery)
				throw new CHttpException(400, 'could not find gallery.');
			
			$this->render('view', array('gallery' => $gallery));
		}
		
		#TODO: commenting, edit details with ajax, thumbs
	}
	
	public function actionRawimage()
	{
		if(!isset($_GET['id']))
			throw new CHttpException(404, 'Could not find image.');
		
		$image = Images::model()->findByPk($_GET['id']);
		
		if(!$image)
			throw new CHttpException(404, 'Could not find image');
		else
		{
			$image_path = YiiBase::getPathOfAlias('application.data.uploads').'/'.$image->id;
			header('Content-Type: image/jpeg');
			readfile($image_path);
			Yii::app()->end();
		}
			
	}
	
	public function actionImage()
	{
		if(!isset($_GET['id']))
			throw new CHttpException(404, 'Could not find image.');	
		
		$image = Images::model()->findByPk($_GET['id']);
		
		if(!$image)
			throw new CHttpException(404, 'Could not find image');
		else
		{
			echo CHtml::tag('img', array('src' => $this->createUrl('//as/gallery/rawimage', array('id' => $_GET['id']))));
			echo CHtml::closeTag('img');
			
			#TODO: use view, edit image details, crop
		}
	}
}
