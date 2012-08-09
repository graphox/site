<?php

class JProtocol
{
	const LOAD_BLOCKS = 0;
	const LOAD_MAP = 1;
	const LOAD_EVENTS = 2;
	const MAP_UPDATE = 10;
	const LISTEN = 100;
}

class DefaultController extends Controller
{
	public function actionAjax()
	{
		$folder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('game.assets'));
		
		$images = array(
			'0' => $folder.'/grass.png',
			'1' => $folder.'/grassclicked.png',
			'2' => $folder.'/path.png',
			'3' => $folder.'/tree.png',
			'4' => $folder.'/water.png',
		);
		
		$map = array(
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
			array( 0, 1, 2, 3, 4),
		);
	
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			if(!isset($_POST['msg']))
				throw new CHttpException(400, 'no msgs');
			
			$messages = $_POST['msg'];
			$response = array();
			
			foreach($messages as $msg) switch($msg[0])
			{
				case JProtocol::LOAD_BLOCKS:
					$response[] = array(JProtocol::LOAD_BLOCKS, $images);
					break;
				
				case JProtocol::LOAD_MAP:
					$response[] = array(JProtocol::LOAD_MAP, 0, $map);
					break;
				
				case JProtocol::MAP_UPDATE:
					$response[] = array(JProtocol::MAP_UPDATE, $msg[1], $msg[2]);
					break;
				
				case JProtocol::LOAD_EVENTS:
					$response[] = array(JProtocol::LOAD_EVENTS, 0, array('click' => 'function(id, y, x, that){ that.updateMap(id, [[4, y, x]]); }'));
					break;
				
				#does nothing special, just sends checks
				case JProtocol::LISTEN:
					$response[] = array(JProtocol::LISTEN);
					
					if(rand(0, 1) == 1)
					{
						$response[] = array(JProtocol::MAP_UPDATE, 0, array(
							array(1, 1, 1)						
						));
					}
					else
					{
						$response[] = array(JProtocol::MAP_UPDATE, 0, array(
							array(0, 1, 1)
						));
					}
					
					break;
				
				default:
					throw new CHttpException(500, 'Protocol mismatch');
					return;
					break;
			
			}
			
			echo CJavaScript::jsonEncode($response);
		}
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
