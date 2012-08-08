<?php

class EntityAccessValidator extends CValidator
{
	public function validateAttribute($object, $field)
	{
		$accessOptions = Entity::getAccessOptions();
		
		if(!isset($accessOptions[$object->$field]))
			$object->addError($field, 'Invalid access.');	
	}
}
