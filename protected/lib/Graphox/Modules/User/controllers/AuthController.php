<?php

namespace User\Controllers;

use \Yii;
use \Doctrine;

class AuthController extends \Controller
{
	public $defaultAction = 'login';
	
	public function actionLogin($service = null)
	{
		/**
		 * Check oauth login
		 */
		/*
		if($service !== null)
        {
            $authIdentity = $this->module->getService($service);
            //$authIdentity->redirectUrl = Yii::app()->user->returnUrl;
            //$authIdentity->cancelUrl = $this->createAbsoluteUrl('site/login');

            if ($authIdentity->authenticate())
			{
                $identity = new EAuthUserIdentity($authIdentity);

                // successful authentication
                if ($identity->authenticate()) {
                    Yii::app()->user->login($identity);

                    // special redirect with closing popup window
                    $authIdentity->redirect();
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $authIdentity->cancel();
                }
            }

            // Something went wrong, redirect to login page
            //$this->redirect(array('site/login'));
        }
		/**
		 * Normal login
		 */
		Yii::import('user.forms.LoginForm');
		$form = new LoginForm();
		
		if(isset($_POST['LoginForm']))
		{
			$form->attributes = $_POST['LoginForm'];
			if($form->validate())
			{
				$form->login();
			}
		}
		
		$this->render('login', array('form' => $form));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
	}
	
	public function actionRegister()
	{
		$form = new \Graphox\Modules\User\Forms\RegisterForm;
		
		if(isset($_POST[get_class($form)]))
		{
			$form->attributes = $_POST[get_class($form)];
			if($form->validate())
			{
				$form->login();
			}
		}
		
		$this->render('register', array('form' => $form));
	}

	public function actionValidate()
	{
		$this->render('validate');
	}
	
	public function actionTest()
	{

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