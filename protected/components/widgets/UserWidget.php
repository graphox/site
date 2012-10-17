<?php

namespace application\components\widgets;

class UserWidget extends \CWidget
{
	public $user;
	
	public function run()
	{
		$this->controller->render('//user/small', array('user' => $this->user));
	}
}
