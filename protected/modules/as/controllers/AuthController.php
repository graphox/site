<?php

class AuthController extends Controller
{
	public function actionIndex()
	{
		$this->actionAuth();
	}
	
	public function actionOauth()
	{
	
	}
	
	public function actionAuth()
	{
		$service = Yii::app()->request->getQuery('service');
		
		if (isset($service))
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
						$user = new User;
						$user->username = $authIdentity->getAttribute('name');
						$user->ingame_password = md5(rand());
						$user->email = $authIdentity->getAttribute('email');
						$user->hashing_method = 'plain';
						$user->web_password = md5(rand());
						$user->salt = md5(rand());
						$user->status = 0;
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
					}
					else
					{
						$user = $externalUser->user;
					}
					
					//Yii::app()->user->login($identity);

					// special redirect with closing popup window
					$authIdentity->redirect(array('auth/success'));
				}
				else
				{
					// close popup window and redirect to cancelUrl
					$authIdentity->cancel();
				}
			}

			// Something went wrong, redirect to login page
			$this->redirect(array('as/auth/login'));
		}
		
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('as.views.site.login',array('model'=>$model));
	}
	
}
