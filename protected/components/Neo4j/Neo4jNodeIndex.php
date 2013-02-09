<?php

class Neo4jNodeIndex extends Neo4jIndex
{
	private $_node;
	private $_conf;
	
	public function __construct($node, $conf = array())
	{
		$this->_node = $node;
		$this->_conf = $conf;
		parent::__construct();
	}
	
	public function getName()
	{
		return $this->getConnection()->getEntityMapper()->getIndexName($this->_node);
	}
	
	public function getConfig()
	{
		return $this->_conf;
	}
}

