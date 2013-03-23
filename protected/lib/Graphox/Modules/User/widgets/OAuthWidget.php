<?php

class OAuthWidget extends CWidget
{
	public function run()
	{
		$path = Yii::app()->getAssetManager()->publish(
				Yii::getPathOfAlias('user.assets').'/auth.png'
		);
		
		$less = Yii::app()->less;
		$less->env['authImage'] = '"'.$path.'"';
		$less->register('user.assets.auth');
		
		Yii::app()->clientScript->registerScriptFile(
				Yii::app()->getAssetManager()->publish(
						Yii::getPathOfAlias('user.assets').'/oauth.js'
				),
				CClientScript::POS_END
		);
		
		$this->render('oauth', array('services' => Yii::app()->getModule('user')->getServices()));
	}
}

