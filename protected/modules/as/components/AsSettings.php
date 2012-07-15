<?php

class AsSettingItem
{
	public $name;
	public $value;
	public $defaultValue;
}


class AsSettings extends CComponent
{
	public function init() {}
	const SETTINGS_VAR = 'as.settings';
	
	/**
		@var settings
		array containing all static site settings
		@readonly please use the set function instead
	*/
	
	private $_settings = null;
	public function getSettings()
	{
		if($this->_settings === null)
			$this->_settings = Yii::app()->getGlobalState(self::SETTINGS_VAR, NULL);
			
		if($this->_settings === null)
		{
			$this->_settings = array();
			$this->load();
		}
		
		return $this->_settings;
	}

	/**
		@function get
		@attribute name the name of the setting
		@attribute default = null the default value
		function to retreive site settings
	*/

	public function get($name, $default = null)
	{
		if(!isset($this->settings[$name]))
			$this->set($name, $default, $default);
	
		return $this->settings[$name]->value;
	}
	/**
		@function set
		@attribute name the name of the setting
		@attribute value the value of the setting
		@attribute default = null the default value
		@attribute writeDown = true option to not write te setting to the database, usefull on massive changes
		function to set the value of a setting
	*/	
	public function set($name, $value, $default = null, $writedown = true)
	{
		$model = new AsSettingItem;
		$model->name = $name;
		$model->value = $value;
		$model->defaultValue = $default;
		$this->_settings[$name] = $model;
		
		if($writedown)
			$this->writeDown();
		
		return $this;
	}
	
	/**
		@function reset
		@attribute name the name of the setting to reset
		resets a setting to its default value
	*/
	public function reset($name)
	{
		if(!isset($this->settings[$name]))
			throw new CException('Could not reset setting, unkown setting');
	
		$this->_settings[$name]->value = $this->_settings[$name]->defaultValue;
		
		return $this;
	}
	
	/**
		@function writeDown
		Writes all settings to the database
	*/
	public function writeDown()
	{
		Yii::app()->setGlobalState(self::SETTINGS_VAR, $this->settings,NULL);
		
	}
	
	/**
		@function load
		loads the settings from the database
	*/	
	public function load()
	{

	}
	
	/**
		@function backup
		write all the settings to a file
	*/
	
	public function backup($file)
	{
	
	}
}
