<?php

class AuthController extends Controller
{
	public function beforeAction($action)
	{
		$config = array();
		switch ($action->id)
		{
			case 'register':
				$config = array(
					'steps'=>array('user'),
					'events'=>array(
						'onStart'=>'wizardStart',
						'onProcessStep'=>'registerWizardstep',
						'onFinished'=>'wizardFinished',
						'onInvalidStep'=>'wizardInvalidStep',
						'onSaveDraft'=>'wizardSaveDraft'
					),
					
					'menuLastItem'=>'Register'
				);
				break;

			case 'activate':
				$config = array(
					'steps'=>array('activate'),
					
					'events'=>array(
						'onStart'=>'wizardStart',
						'onProcessStep'=>'registerWizardstep',
						'onFinished'=>'wizardFinished',
						'onInvalidStep'=>'wizardInvalidStep',
						'onSaveDraft'=>'wizardSaveDraft'
					),
					
					'menuLastItem'=>'Register'
				);
				break;

			default:
				break;
		}
		if (!empty($config))
		{
			$config['class']='application.components.WizardBehavior';
			$this->attachBehavior('wizard', $config);
		}
		
		return parent::beforeAction($action);
	}

	public function wizardStart($event)
	{
		$event->handled = true;
	}
	
	public function wizardInvalidStep($event)
	{
		Yii::app()->getUser()->setFlash('error', $event->step.' is not a vaild step.');
	}

	public function wizardFinished($event)
	{
		$steps = $event->sender->read();
		$models = $this->wizardGetModels();
		
		foreach($steps as $step => $data)
		{
			$model = new $models[strtolower($step)]();
			if(!$model->do_delay_save($data))
				throw new Exception('could not save!');
		}
		
		if ($event->step===true)
			$this->render('completed', compact('event'));
		else
			$this->render('finished', compact('event'));

		$event->sender->reset();
		Yii::app()->end();
	}
	
	public function wizardGetModels()
	{
		Yii::import('as.models.forms.*');
		
		return array(
			'user' => 'RegisterForm',
			'profile' => 'RegisterForm',
			'activate' => 'ActivateForm',
		);	
	}
	
	public function registerWizardStep($event)
	{
		$models = $this->wizardGetModels();
		
		$model = new $models[strtolower($event->step)]();
		$model->attributes = $event->data;
		
		
		$form = $model->getForm();

		// Note that we also allow sumission via the Save button
		if ($form->submitted() && $form->validate())
		{
			$event->sender->save($model->delay_save());
			$event->handled = true;
		}
		else
			$this->render('form', compact('event','form'));
	}	

	public function actionIndex()
	{
		$this->actionAuth();
	}
	
	public function actionOauth()
	{
	
	}
	
	public function actionRegister($step = null)
	{
		$this->pageTitle = 'Registration Wizard';
		$this->process($step);
	}
	
	public function actionActivate($step = null)
	{
		$this->pageTitle = 'activation Wizard';
		$this->process($step);
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
			{
				Yii::app()->user->setFlash('success', Yii::t('AS.auth', 'Your login was successfully.'));
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		
		// display the login form
		$this->render('as.views.auth.login',array('model'=>$model));
	}
	
}
