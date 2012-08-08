<?php

class EntityStatusValidator extends CValidator
{
	public function validateAttribute($object, $field)
	{
		$accessOptions = Entity::getStatusOptions();
		
		if(!isset($accessOptions[$object->$field]))
			$object->addError($field, 'Invalid status.');	
	}
}
