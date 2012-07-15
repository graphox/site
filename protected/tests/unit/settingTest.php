<?php
class settingsTest extends CTestCase
{
	public function testModule()
	{
		$this->assertNotEquals(NULL, Yii::app()->settings);
	}
	
	public function testCRUD()
	{
		Yii::app()->settings->set('test', 'value', 'ok');
		$this->assertEquals('value', Yii::app()->settings->settings['test']->value);
		Yii::app()->settings->reset('test');
		$this->assertEquals('ok', Yii::app()->settings->get('test'));
		
		Yii::app()->settings->writedown();

		#test global state storage
		$settings = new AsSettings;
		$this->assertEquals('ok', $settings->get('test'));
		$settings->writeDown();
				
		Yii::app()->setGlobalState(AsSettings::SETTINGS_VAR, NULL);
		
		#test db storage
		#$settings = new AsSettings;
		#$this->assertEquals('ok', $settings->get('test'));
	}
}

