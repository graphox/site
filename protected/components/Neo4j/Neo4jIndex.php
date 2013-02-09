<?php

class Neo4jIndex extends Everyman\Neo4j\Index\NodeIndex
{
	public function __construct($config = array())
	{
		parent::__construct($this->getConnection(), $this->getName(), $this->getConfig());
	}
	
	public function getName()
	{
		return $this->getConnection->getMapper()->getIndexName($this);
	}
	
	public function getConfig()
	{
		return array();
	}
	
	public function connection()
	{
		return 'neo4j';
	}
	
	public function getConnection()
	{
		return Yii::app()->{$this->connection()};
	}
}

