<?php

class Neo4jRelation extends Neo4jModel
{
		
	/**
	 * @var \Everyman\Neo4j\Relationship reference to relation in database
	 */
	private $_r;
	
	private $_t;

	
	public function afterConstruct()
	{
		if($this->scenario !== Scenario::MODEL)
		{
			$this->_n = $this->getConnection()->makemakeRelationship();
		}
		
		parent::afterConstruct();
	}
	
	public function setAttribute($property, $value)
	{
		$this->_r->setProperty($property, $value);
	}
	
	public function getAttribute($property)
	{
		return $this->_r->getProperty($property);
	}

	public function __get($name)
	{
		if($this->propertyExist($name))
			return $this->getAttribute ($name);
		else
			return parent::__get($name);
	}
	
	public function __set($name, $value)
	{
		if($this->propertyExist($name))
			return $this->setAttribute ($name, $value);
		else
			return parent::__set($name, $value);
	}
	
	public function getId()
	{
		if($this->isNew())
			throw new CException('Cannot get id from unsaved model.');
		
		return $this->_r->getId();
	}
	
	public function attributeNames()
	{
		return array_keys($this->properties());
	}
	
	protected function defaultCriteria()
	{
		return array(
			//'relation' => self::model()
		);
	}
	
	protected function initCriteria()
	{
		$this->_criteria = new Neo4jDbCriteria(
			$this->defaultCriteria()
		);
	}

	public function populateRecord($data, $id)
	{
		parent::populateRecord($data, $id);
		$this->_n->setId($id);
	}
}

