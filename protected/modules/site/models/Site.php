<?php

class Site extends CModel
{
	private $name			= 'Sauers';
	
	private $title			= 'Welcome to sauers!';
	private $description	= 'An online community to do shit!';
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function attributeNames()
	{
		return array(
			'name',
			'description'
		);
	}
	
	public function init()
	{
		
	}
}

