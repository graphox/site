<?php

class PrivacySettings extends CModel
{
	//TODO: move to simpleModel?
	public function __construct($stuff = NULL)
	{
		parent::__construct();
		
		if($stuff)
			$this->setAttributes($stuff);
	}
	
	public $toFriends	= true;
	public $toPublic	= true;
}
