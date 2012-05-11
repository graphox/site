<?php

class RegisterForm extends CFormModel
{
	public function rules()
	{
		return array(
			#required fields
			array('username, ingame_password, email, web_password, captcha', 'required'),
			
			#other rules
			array('email', 'email'),
			array('ingame_password', 'compare', 'operator' => '!=')
			array('username', 'unique', 'attributeName' => 'username', 'caseSensitive' => false, 'classname' => 'User')
		);
	}
}
