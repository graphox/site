<?php

class AuthController extends AsController
{
	public $defaultAction = 'auth';
	
	public function actionRegister()
	{
		$model = new RegisterForm;
		
		$this->performAjaxValidation($model, 'register-form');
		
		if(isset($_POST['RegisterForm']))
		{
			$model->attributes = $_POST['RegisterForm'];
			if($model->save())
				Yii::app()->user->setFlash('register.success', 'successfully registered your account, please check your inbox to activate your account');
		}
		
		$this->render('register', array('model' => $model));
	}
	
	public function actionActivate($step = null)
	{
		$model = new Activationkey('activate');
		
		if(isset($_POST['Activationkey']))
		{
			$model->attributes = $_POST['Activationkey'];
			
			if($model->validate())
				Yii::app()->user->setFlash('activate.success', 'successfully activated your account. you can now log in.');
		}
		
		$this->render('activate', array('model' => $model));
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionAuth()
	{
		if(!Yii::app()->user->isGuest)
		{
			Yii::app()->getUser()->setFlash('error', 'You are already logged in.');
			$this->redirect(array('/'));
		}
		$service = Yii::app()->request->getQuery('service');
		$error = Yii::app()->request->getQuery('error');

		if(isset($_GET['returnurl']))
			Yii::app()->user->setReturnUrl($_GET['returnurl']);

		if(isset($error))
		{
			switch($error)
			{
				default:
				case 'unknown':
					echo Yii::t('AS.auth', 'An unkown error has occured while authentication you.');
					break;
			}
		}
		elseif(isset($service))
		{
			$authIdentity = Yii::app()->eauth->getIdentity($service);
			$authIdentity->redirectUrl = Yii::app()->user->returnUrl;
			$authIdentity->cancelUrl = $this->createAbsoluteUrl('as/auth/cancel');
			
			if ($authIdentity->authenticate())
			{
				if($authIdentity->isAuthenticated)
				{
					$externalUser = ExternalUser::model()->findByAttributes(array('external_user_id' => $authIdentity->id));
					
					if($externalUser === null)
					{
						$name = $authIdentity->getAttribute('name');
						while(User::model()->findByAttributes(array('username' => $name)))
						{
							$name = $name.(rand() % 10);
						}
						
						$user = new User;
						$user->username = $name;
						$user->ingame_password = md5(rand());
						$user->email = $authIdentity->getAttribute('email');
						$user->hashing_method = 'plain';
						$user->web_password = md5(rand());
						$user->salt = md5(rand());
						$user->status = UserStatus::OAUTH_ACCOUNT;
						if(!$user->save())
						{
							$errors = $user->getErrors();
							throw new exception(print_r($errors, true));
						}
					
						$user_id = $user->id;
					
						$provider = OauthProvider::model()->findByAttributes(array('name' => $authIdentity->getServiceName()));
						
						if($provider === null)
						{
							$provider = new OauthProvider;
							$provider->name = $authIdentity->getServiceName();
							$provider->logo_url = $authIdentity->getServiceTitle();
							$provider->privatekey = '';
							$provider->dialect = $authIdentity->getServiceType();
							if(!$provider->save())
							{
								$errors = $provider->getErrors();
								throw new exception(print_r($errors, true));
							}
						}
					
						$provider_id = $provider->id;
					
						$external = new ExternalUser;
						$external->user_id = $user_id;
						$external->oauth_provider_id = $provider_id;
						$external->key = rand();
						$external->external_user_id = $authIdentity->id;
						if(!$external->save())
						{
							$errors = $external->getErrors();
							throw new exception(print_r($errors, true));
						}
						
						#add to world group
						AccessControl::AddGroupToUser($user);
					}
					else
					{
						$user = $externalUser->user;
					}
					
					$identity = new OAuthUserIdentity($user);
					Yii::app()->user->login($identity, 0);
					Yii::app()->user->setFlash('success', Yii::t('AS.auth', 'Your oauth login was successfully.'));

					// special redirect with closing popup window
					$authIdentity->redirect(Yii::app()->user->returnUrl);
					
				}
				else
				{
					// close popup window and redirect to cancelUrl
					$authIdentity->cancel();
				}
			}

			// Something went wrong, redirect to login page
			Yii::app()->user->setFlash('error', Yii::t('AS.auth', 'Your oauth login failed.'));
			$this->redirect(array('auth/auth', 'error' => 'oauth'));
		}
		
		$model=new User('login');

		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			
			if($model->validate() && $model->login())
			{
				Yii::app()->user->setFlash('success', Yii::t('AS.auth', 'Your login was successfully.'));
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		
		$model->password = '';
		
		// display the login form
		$this->render('as.views.auth.login',array('model'=>$model));
	}
	
}
