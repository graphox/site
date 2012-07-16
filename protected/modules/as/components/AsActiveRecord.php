<?php

class AsActiveRecord extends CActiveRecord
{
	public function findOrCreate($search, $additional = array())
	{
		$result = $this->findByAttributes($search);

		if(!$result)
		{
			$class = get_class($this);
			
			$result = new $class;
			$result->setAttributes(array_merge($search, $additional), true);
			if(!$result->save(false))
				throw new CException('could not save');
		}
		
		return $result;
	}

	public function findAllOrCreate($search, $additional = array())
	{
		$result = $this->findAllByAttributes($search);

		if(!$result)
		{
			$class = get_class($this);
			
			$result = new $class;
			$result->setAttributes(array_merge($search, $additional), true);
			if(!$result->save(false))
				throw new CException('could not save');
		}
		
		return $result;
	}
	
	public function findAllWithAccess($actions)
	{
		$all = $this->findAll();
		
		$return = array();
		
		foreach($all as $object)
		{
			$rules = (array) Yii::app()->accessControl->getAccess($object->aclObject, $actions);
			
			if(!in_array(false, $rules))
				$return[] =& $object;
		}
		
		return $return;
	}

	/**
	 * @return the fields to set to boolean
	 */
	
	protected function getBooleanFields()
	{
		return array();
	}
	
	/**
	 * Sets the tinyint fields to bool
	 */
	protected function afterFind()
	{
		foreach($this->booleanFields as $field)
			if($this->$field === '1' || $this->$field === 1)
				$this->$field = true;
			else
				$this->$field = false;
		
		return parent::afterFind();
	}
	
	/**
	 * Reset fields back to ints before save
	 */
	protected function beforeSave()
	{
		foreach($this->booleanFields as $field)
			if($this->$field === true)
				$this->$field = 1;
			elseif($this->$field === false)
				$this->$field = 0;
		
		return parent::beforeSave();
	}
}
