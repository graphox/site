<?php

class Site extends CComponent
{

	public function init()
	{
	
	}
	
	public function getId()
	{
		static $id;
		
		if(!isset($id))
			$id = $this->createId();
		
		return $id;	
	}
	
	public function createId()
	{
		return Yii::app()->params['id'];
	}
}
