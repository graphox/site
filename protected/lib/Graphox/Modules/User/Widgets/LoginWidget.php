<?php

namespace Graphox\Modules\User\Widgets;

use \Yii;

class LoginWidget extends \CWidget
{
	public $showRegister = true;
	public $form;
	public $noBox = false;
	
	public function run()
	{
		if(!isset($this->form))
		{
			Yii::import('user.forms.LoginForm');
			$this->form = new LoginForm();
		}
		
		$this->render('login', array('showRegister' => $this->showRegister, 'formBuilderModel' => $this->form, 'noBox' => $this->noBox));
	}
}

