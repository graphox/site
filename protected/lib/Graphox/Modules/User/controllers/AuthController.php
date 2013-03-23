<?php

namespace User\Controllers;

use \Yii;
use \Doctrine;

class AuthController extends \Controller
{
	public $defaultAction = 'login';

	public function actionLogin($service = null)
	{
		$form = new \Graphox\Modules\User\Forms\LoginForm();

		if(isset($_POST[get_class($form)]))
		{
			$form->attributes = $_POST[get_class($form)];
			if($form->validate() && $form->login())
			{
				Yii::app()->user->setFlash('success', Yii::t('user.login', 'Successfully logged in.'));
				$this->redirect(array('/user/timeline'));
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
			if($form->save())
			{
				Yii::app()->user->setFlash('success', Yii::t('user.register', 'Successfully registered account.'));
				$this->redirect(array('activate'));
			}
		}

		$this->render('register', array('form' => $form));
	}

	public function actionActivate()
	{
		$form = new \Graphox\Modules\User\Forms\ActivationForm;

		if(isset($_POST[get_class($form)]))
		{
			$form->attributes = $_POST[get_class($form)];
			if ($form->activate())
            {
                Yii::app()->user->setFlash('success',
                        Yii::t('user.activate', 'Successfully activated user.'));
            }
		}

		$this->render('activate', array('form' => $form));
	}

}