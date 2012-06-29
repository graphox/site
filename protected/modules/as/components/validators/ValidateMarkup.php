<?php

class ValidateMarkup extends CValidator
{
	public function validateAttribute($object, $field)
	{
		static $allowed;
		
		if(!isset($allowed))
			$allowed = ContentMakeup::userAllowed();
		
		if(!isset($allowed[$object->$field]))
			$object->addError($field, 'Invalid makrup style: '.$object->$field);
	}
}
