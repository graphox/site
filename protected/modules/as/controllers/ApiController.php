<?php

class ApiController extends Controller
{
	public $app;

	public function init()
	{
		//since these may be set later
		isset($_GET['api-key']) && $_REQUEST['api-key'] = $_GET['api-key'];
		isset($_GET['app-id']) && $_REQUEST['app-id'] = $_GET['app-id'];
		
		if(isset($_GET['debug']))
			print_r($_REQUEST);
		
		if(!isset($_REQUEST['api-key']) || !isset($_REQUEST['app-id']))
		{
			echo json_encode(array('success' => false, 'error' => 'no api-key / app-id provided!'));
			Yii::app()->end();
		}
		
		$api = Api::model()->findByPk($_REQUEST['app-id']);
		
		if(!$api)
		{
			echo json_encode(array('success' => false, 'error' => 'invalid app id!'));
			Yii::app()->end();			
		}
		
		if(md5($api->key.$api->id) != $_REQUEST['api-key'])
		{
			echo json_encode(array('success' => false, 'error' => 'invalid app key!'));
			Yii::app()->end();
		}
		
		$this->app = $api;
		
		if(!isset($_POST['action']))
		{
			echo json_encode(array('success' => false, 'error' => 'No postvar action was given!'));
			Yii::app()->end();
		}
		
		switch($_POST['action'])
		{
			case 'login':
				if(!isset($_POST['username']) || !isset($_POST['password']))
				{
					echo json_encode(array('success' => false, 'error' => 'No postvar username/password was given!'));
					Yii::app()->end();
				}
				
				$userIdentity = new UserIdentity($_POST['username'], $_POST['password']);
				$key = $userIdentity->ApiAuth();
				
				if($userIdentity->errorCode===UserIdentity::ERROR_NONE)
				{
					Yii::app()->user->login($userIdentity, 0);
					echo json_encode(array('success' => true, 'message' => 'Successfully logged in.', 'user-key' => $key));
					Yii::app()->end();
				}
				else
				{
					echo json_encode(array('success' => false, 'error' => 'Invalid username/password!'));
					Yii::app()->end();				
				}
				
				break;
			
			case 'stats':
				echo json_encode($this->handleAction($_POST['action']));
				Yii::app()->end();
			
			default:
				echo json_encode(array('success' => false, 'error' => 'Invalid action!'));
				Yii::app()->end();
				break;
		}
	}
	
	public function requireUser($end = true)
	{
		if(!isset($_POST['user']) || !isset($_POST['user-key']))
		{
			echo json_encode(array('success' => false, 'error' => 'No postvar user/user-key was given!'));
			Yii::app()->end();
		}
		
		$userIdentity = new UserIdentity('', '');
		$userIdentity->ApiCheckAuth($_POST['user'], $_POST['user-key']);
				
		if($userIdentity->errorCode===UserIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($userIdentity, 0);
			return true;
		}
		elseif($end)
		{
			echo json_encode(array('success' => false, 'error' => 'Invalid user!'));
			Yii::app()->end();		
		}
		else
			return false;
	}
	
	
	public function index()
	{
	}
	
	public function handleAction($action)
	{
		return array('success' => true, 'notice' => 'not implemented!');
	}
}
