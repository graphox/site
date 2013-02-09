<?php

class SiteController extends Controller
{
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	elseif($error['code'] == 404)
			{
				try
				{
					$this->actionView(str_replace(Yii::app()->request->getScriptUrl(), '', Yii::app()->request->url));
					return;
				}
				catch(CHttpException $e)
				{
					if($e->statusCode === 404)
						try
						{
							$this->actionView('404');
							return;
						}
						catch(CHttpException $e_)
						{
							$e = $e_;
						}
					
					$error['data'] = array(
						'code'		=>	$e->getCode(),
						'message'	=>	$e->getMessage(),
						'file'		=>	$e->getFile(),
						'line'		=>	$e->getLine(),
						'trace'		=>	$e->getTrace()
					);
				}
			}
			
			$this->render('error', $error);
			
	    }
	}
	
	public function actionView($name)
    {
		$name = str_replace(Yii::app()->request->baseUrl, '', $name);
		if(isset($name[0]) && $name[0] == '/')
			$name = substr($name, 1);
		
		$model = Page::model()->findByAttributes(array('routeName' => $name));

		if(!$model)
			throw new CHttpException(404, 'Could not find page.');

		$this->render('view',array(
            'model'=> $model,
        ));
    }
}
