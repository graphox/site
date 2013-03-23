<?php

Yii::import('user.services.BaseOAuthService');
Yii::import('user.services.github.*');

class GitHubOAuthService extends BaseOAuthService
{
	protected $clientId;
	protected $clientSecret;
	
	public function setClientId($id)
	{
		$this->clientId = $id;
	}

	public function setClientSecret($secret)
	{
		$this->clientSecret = $secret;
	}
	
	public function info()
	{
		return array(
			'id'	=> 'github',
			'name'	=> Yii::t('user.oauth', 'GitHub'),
			'scopes' => array('user:email', 'notifications'),
		);
	}
	
	public function getInfo()
	{
		return $this->info();
	}

	public function getId()
	{
		return $this->info['id'];
	}
	
	public function getTitle()
	{
		return $this->info['name'];
	}
	
	public function getHtmlClass()
	{
		return 'oauth-'.$this->getId();
	}
	
	public function getState()
	{
		if(isset(Yii::app()->session['github:csrfState']))
		{
			return Yii::app()->session['github:csrfState'];
		}
		
		$rnd = rand(0, 9);
		
		while(($next = rand(0, 9)) !== 0)
				$rnd .= $next;
		
		Yii::app()->session['github:csrfState'] = $rnd;
		return $rnd;		
	}
	
	public function redirectUrl()
	{
		return 'https://github.com/login/oauth/authorize?'.http_build_query(array(
			'scope'			=> implode(',', $this->info['scopes']),
			'client_id'		=> $this->clientId,
			'state'			=> $this->getState(),
			'redirect_url'	=> Yii::app()->request->getServerName().Yii::app()->request->getRequestUri()
		));
	}
	
	public function makeRequest(ACurl $request)
	{
		$request->options->addHeader('Accept', 'application/vnd.github.v3+json');
		$response = $request->exec();
		
		
		$json = $response->fromJSON();
		if($response->isError || (isset($json['error']) && (($json['message'] = $json['error']) || true)))
		{
			if(isset($json['message']))
			{
				if(isset($json['errors']))
					$json['message'] .= print_r($json['errors'], true);
					
				throw new CException($json['message']);
			}
		}
		
		var_dump($request);
		var_dump($response);
		
		return $json;
	}


	public function authenticate()
	{
		/**
		 * Before authentication, redirect to provider
		 */
		if(isset($_GET['status']) && $_GET['status'] == 'redirect')
		{
			Yii::app()->controller->redirect(
				$this->redirectUrl()
			);
		}
		
		if(isset($_GET['error']))
		{
			if($_GET['error'] === 'access_denied')
				return $this->closeWindow();
			else
				throw new CException($_GET['error']);
		}
		
		//request was successful
		if(isset($_GET['code']))
		{
			if(!isset($_GET['state']) || $_GET['state'] !== $this->getState())
				throw new CHttpException(400, 'State check failed.');
			
			Yii::import('ext.curl.*');
			
			$request = new ACurl;
			$request->options->addHeader('Accept', 'application/json');
			$data = $this->makeRequest($request->post('https://github.com/login/oauth/access_token', array(
				'client_id'		=> $this->clientId,
				'client_secret'	=> $this->clientSecret,
				'code'			=> $_GET['code'],
				'state'			=> $_GET['state']
			), false));
			
			if(isset($data['access_token']))
			{
				Yii::import('user.services.github.*');
				return new GithubUserIdentity($data['access_token']);
			}
			else
				throw new CException('error');
			
			
			
			die;
		}
	}
	
	public function closeWindow()
	{
		ob_clean();
		
		echo '<!DOCTYPE html><html><body><script type="text/javascript">window.close()</script></body></html>';
		
		Yii::app()->end();
	}
}
