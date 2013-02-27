<?php

namespace Graphox\Modules\User\Forms;

use \Yii;
use \CHtml;

class ActivationForm extends \CFormModel
{
	public $key;
 
	public function rules()
	{
		return array(
			array('key', 'required'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'key'	=> Yii::t('user.activate', 'The activation key you received by mail.'),
		);
	}
	
	public function getFormConfig()
	{
		return array(
			'title' => 'Activate your account',
			'showErrorSummary' => true,
			'action' => array( '/user/auth/activate' ),
			'elements' => array(
				'key' => array(
					'type' => 'text',
					'maxlength' => 32,
					'hint'			=> Yii::t('user.activate', 'The activation key you received by email.'),
					'placeholder'	=> Yii::t('user.activate', 'Your key'),
					'class' => 'input-large',
					'append' => '<i class="icon-user"></i>',
				),
			),
			
			 'buttons' => array(
				 'submit' => array(
					'type'			=> 'submit',
					'layoutType'	=> 'primary',
					'label'			=> Yii::t('user.activate', 'Log in'),
				)
			),
		);
	}
	
		/**
	 * Validate the username/email - password combination
	 */
	public function activate()
	{
		if($this->hasErrors())
			return;

		$emailRepository = Yii::app()->neo4j->getRepository('\Graphox\Modules\User\Email');
		/**
		 * @var \Graphox\Modules\User\User
		 */
		$email = $emailRepository->findOneByActivationKey($this->key);
		
		if($email !== null)
		{
			$email->setIsActivated(true);
			$email->setActivationKey(null);
			Yii::log('User activated account.', \CLogger::LEVEL_INFO);
			return;
		}
		
		Yii::log('User tried to register invalid key', \CLogger::LEVEL_WARNING);
		$this->addError('key', 'Invalid key.');
	}

}

