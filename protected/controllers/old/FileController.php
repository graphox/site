<?php

/**
 * File upload controller
 */
class FileController extends Controller
{
	public $uploadPath;
	public function beforeAction($action)
	{
		Yii::setPathOfAlias('xupload', Yii::getPathOfAlias('ext.xupload'));
		
		Yii::import( "xupload.models.XUploadForm" );
		@mkdir($this->uploadPath = Yii::getPathOfAlias('application.uploads'));
		@mkdir(Yii::getPathOfAlias('application.uploads.public'));
		
		return parent::beforeAction($action);
	}
	
	public function actionIndex()
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, 'Please login to upload files.');
		
		$this->render('index');		
	}
	public function actionUpload()
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, 'Please login to upload files.');
		$model = new File;
		
		$this->render('upload', array(
			'model' => $model,
		));
	}
	
    public function actionUploadFile()
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, 'Please login to upload files.');
		//This is for IE which doens't handle 'Content-type: application/json' correctly
		header( 'Vary: Accept' );
		if( isset( $_SERVER['HTTP_ACCEPT'] ) 
			&& (strpos( $_SERVER['HTTP_ACCEPT'], 'application/json' ) !== false) ) {
			header( 'Content-type: application/json' );
		} else {
			header( 'Content-type: text/plain' );
		}

		//Here we check if we are deleting and uploaded file
		if( isset( $_GET["_method"] ) ) {
			if( $_GET["_method"] == "delete" ) {
				if( $_GET["file"][0] !== '.' ) {
					$file = $path.$_GET["file"];
					if( is_file( $file ) ) {
						unlink( $file );
					}
				}
				echo json_encode( true );
			}
		} else {
			$model = new File('upload');
			if(isset($_POST['File']))
				$model->attributes=$_POST['File'];
			$model->file = CUploadedFile::getInstance( $model, 'file' );
			//We check that the file was successfully uploaded
			if( $model->file !== null )
			{
				//Grab some data
				$model->mimeType = $model->file->getType();
				$model->size = $model->file->getSize();
				$model->name = $model->file->getName();
				//(optional) Generate a random name for our file
				$filename = md5( Yii::app( )->user->id.microtime( ).$model->name);
				$filename .= ".".$model->file->getExtensionName( );
				if( $model->validate())
				{
					#save the file under a random name
					$fileName = Yii::getPathOfAlias('application.uploads').'/'.$model->name;
					while(file_exists($fileName.$model->file->getExtensionName()))
					{
						$fileName .= rand(0, 9);
					}
					$fileName .= $model->file->getExtensionName();
					$model->file->saveAs( $fileName);
					$model->fsName = $fileName;
					
					#save the file under a random name
					$routeName = $model->routeName;
					while(File::model()->findByAttributes(array('routeName' => $routeName)) !== NULL)
					{
						$routeName .= rand(0, 9);
					}
					$model->routeName = $routeName;
					
					if($model->save(false))
					{
						$model->setCreator(Yii::app()->user->id);
						echo json_encode( array( array(
							"name" => $model->name,
							"type" => $model->mimeType,
							"size" => $model->size,
							"url" => $model->url,
							"thumb" => $model->thumbUrl,
						) ) );
					}
					else
					{
						Throw new CException('Something bad happened.');
					}
				} else {
					//If the upload failed for some reason we log some data and let the widget know
					echo json_encode( array( 
						array( "error" => $model->getErrors( 'file' ),
					) ) );
					Yii::log( "XUploadAction: ".CVarDumper::dumpAsString( $model->getErrors( ) ),
						CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction" 
					);
				}
			} else {
				throw new CHttpException( 500, "Could not upload file" );
			}
		}
	}
	
	public function actionRaw($name)
	{
		$model = File::model()->findByAttributes(array('routeName' => $name));
		
		if($model === null)
			throw new CHttpException(404, 'File not found.');
		
		if(isset($_GET['thumb']))
		{
			if($model->isImage())
			{
				if(!file_exists($model->thumbName))
				{
					$thumb = Yii::app()->phpThumb->create($model->fsName);
					$thumb->resize(100,100);
					$thumb->save($model->thumbName);
				}
				else
				{
					$thumb = Yii::app()->phpThumb->create($model->thumbName);	
				}
			}
			else
			{
				throw new CHttpException(500, 'No thumb available for non-img files yet.');
			}

			$thumb->show();
			Yii::app()->end();
		}
		elseif(!isset($_GET['download']) && $model->isImage())
		{
			header( 'Content-type: '.$model->mimeType );
			echo file_get_contents($model->fsName);
			Yii::app()->end();
		}
		else
		{
			Yii::app()->request->sendFile(
				$model->name,
				file_get_contents($model->fsName),
				$model->mimeType
			);
		}
	}
	
	public function actionEdit($id, $ajax = false)
	{
		$model = File::model()->findById($id);
		
		if(!$model)
				Throw new CHttpException(404, 'Could not find model');
		
		if(isset($_POST['File']))
		{
			$model->attributes = $_POST['File'];
			

			$f = File::model()->findByAttributes(array('routeName' => $model->routeName));		

			if($f !== NULL && $model->id !== $f->id)
				$model->addError('routeName', 'That name already exists.');
			elseif($model->save())
			{
				$this->redirect(array('file/index'));
			}
		}
		
		if($ajax)
			$this->renderPartial ('_edit_form', array('model' => $model));
		else
			$this->render('edit', array('model' => $model));
	}

}