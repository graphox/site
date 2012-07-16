<?php

class AsInArrayValidator extends CValidator
{
	public $arrayValues = true;
	public $data;
	
	public function validateAttribute($object, $field)
	{
		if(
			($this->arrayValues && !in_array($object->$field, $this->data))
			||
			(!$this->arrayValues && !isset($this->data[$object->$field]))
		)
			$object->addError($field, 'Invalid value');
	}
}
