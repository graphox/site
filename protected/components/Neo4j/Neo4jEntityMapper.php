<?php

class Neo4jEntityMapper extends Everyman\Neo4j\EntityMapper
{
	public function getClassField()
	{
		return "modelclass";//'className';
	}
	
	public function getClassName($class)
	{
		return str_replace('\\', '.', get_class($class));
	}
	
	public function getIndexName($index)
	{
		return str_replace('\\', '.', get_class($index));
	}
	
	public function parseClassPath($name)
	{
		return '\\'.str_replace('.', '\\', $name);
	}
	
	public function getEntitiesFromResultSet($result)
	{
		
		$return = array();
		foreach($result as $row)
		{
			$return[] = $row[0];
		}

		return $return;
	}
	
	/**
	 * Generate and populate a node from the given data
	 *
	 * @param array $data
	 * @return Node
	 * /
	public function makeNode($data)
	{
		if(isset($data['data'][$this->getClassField()]))
			$class = $this->parseClassPath ($data['data'][$this->getClassField()]);
		else
			return parent::makeNode ($data);

		$i = $class::model()->instantiate($data);
		$i->populateRecord($this->getIdFromUri($data['self']), $data);
		
		return $i;
	}*/
}

