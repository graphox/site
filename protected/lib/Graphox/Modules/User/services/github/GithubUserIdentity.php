<?php

Yii::import('ext.curl.*');

class GithubUserIdentity extends UserIdentity
{
	private $accessToken;

	public $userName;
	public $id;
	public $avatarUrl;

    /*
      "avatar_url": "https://github.com/images/error/octocat_happy.gif",
      "gravatar_id": "somehexcode",
      "url": "https://api.github.com/users/octocat",
      "name": "monalisa octocat",
      "company": "GitHub",
      "blog": "https://github.com/blog",
      "location": "San Francisco",
      "email": "octocat@github.com",
      "hireable": false,
      "bio": "There once was...",
      "public_repos": 2,
      "public_gists": 1,
      "followers": 20,
      "following": 0,
      "html_url": "https://github.com/octocat",
      "created_at": "2008-01-14T04:33:35Z",
      "type": "User" */

    public function __construct($accessToken)
	{
		$this->accessToken = $accessToken;
	}

	public function fetchData()
	{
		$userInfo = Yii::app()	->getModule('user')
								->getService('github')
								->makeRequest(
										$request->get('https://api.github.com/user?'.  http_build_query(array(
											'access_token'	=> $this->accessToken
										)), false));


		$user->avatarUrl	= $userInfo['avatar_url'];
		$user->content		= $userInfo['bio'];
		$user->email		= $userInfo['email'];
		$user->name			= $userInfo['name'];
	}
}