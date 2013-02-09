<?php
Yii::import('user.services.BaseOAuthService');
class TwitterOAuthService extends BaseOAuthService
{
	protected $name = 'twitter';
	protected $title = 'Twitter';
	protected $type = 'OAuth';
	protected $jsArguments = array('popup' => array('width' => 900, 'height' => 550));
	protected $key = '';
	protected $secret = '';
	protected $providerOptions = array(
		'request' => 'https://api.twitter.com/oauth/request_token',
		'authorize' => 'https://api.twitter.com/oauth/authenticate', //https://api.twitter.com/oauth/authorize
		'access' => 'https://api.twitter.com/oauth/access_token',
	);
	
	public function setKey($key)
	{
		$this->key = $key;
	}

	public function setSecret($secret)
	{
		$this->secret = $secret;
	}	

	protected function fetchAttributes()
	{
		$info = $this->makeSignedRequest('https://api.twitter.com/1/account/verify_credentials.json');

		$this->attributes['id'] = $info->id;
		$this->attributes['name'] = $info->name;
		$this->attributes['url'] = 'http://twitter.com/account/redirect_by_id?id=' . $info->id_str;

		/* $this->attributes['username'] = $info->screen_name;
		  $this->attributes['language'] = $info->lang;
		  $this->attributes['timezone'] = timezone_name_from_abbr('', $info->utc_offset, date('I'));
		  $this->attributes['photo'] = $info->profile_image_url; */
	}

	/**
	 * Authenticate the user.
	 * @return boolean whether user was successfuly authenticated.
	 */
	public function authenticate()
	{
		if (isset($_GET['denied']))
			$this->cancel();

		return parent::authenticate();
	}

}
