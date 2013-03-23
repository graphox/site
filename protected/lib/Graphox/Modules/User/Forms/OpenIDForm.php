<?php

class OpenIDForm extends CFormModel
{
	public $url;
 
	public function rules()
	{
		return array(
			array('url', 'required'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'url'	=> Yii::t('user.openid', 'Openid url'),
		);
	}
	
	public function getFormConfig($showRegister = true)
	{
		return array(
			'title' => 'Openid login',
			'showErrorSummary' => true,
			'action' => array( '/user/auth' ),
			'elements' => array(
				'url' => array(
					'type' => 'text',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.openid', 'The url of your account'),
					'placeholder'	=> Yii::t('user.openid', 'Your openid url'),
					'class' => 'input-large',
					'append' => '<i class="icon-user"></i>',
				),
			),
			
			 'buttons' => array(
				 'submit' => array(
					'type'			=> 'submit',
					'layoutType'	=> 'primary',
					'label'			=> Yii::t('user.login', 'Log in'),
				)
			),
		);
	}
	
	}

