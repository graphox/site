<?php

class Neo4jDbCriteria
{
	private static $PARAMIDX = 0;
	const DEFAULT_PARAM_NAME = 'YII';
	
	public $startNode	= null;
	public $index		= null;
	public $filter		= null;
	
	public $param		= array();
	
	public $relation	= '';
	public $sort		= '';
	
	public $limitCount	= null;
	public $limitOffset	= 0;
	
	public function __construct($data)
	{
		if(!is_array($data))
			$data = $data->toArray();
		
		foreach($data as $k => $v)
			$this->$k = $v;
	}
	
	private function getNextParam($name = self::DEFAULT_PARAM_NAME)
	{
		return $name.self::$PARAMIDX++;
	}
	
	public function registerParam($value, $name = self::DEFAULT_PARAM_NAME)
	{
		$name = $this->getNextParam($name);
		$this->param[$name] = $value;
		return $name;
	}
	
	public function addCondition($filter, $operator = 'AND')
	{
		if($this->filter != null)
			$this->filter = '( '.$this->filter.' '.$operator.' '.$filter.' )';
		else
			$this->filter = $filter;
		
		return $this;
	}
	
	public function match($field, $value, $operator = 'AND', $match = '==')
	{
		$fieldP = $this->registerParam($field);
		$valueP = $this->registerParam($value);
				
		$filter = '( it[('.$fieldP.')] '.$match.' '.$valueP.' )';
		
		return $this->addCondition($filter, $operator);
	}
	
	public function compare($field, $value, $match = '==', $operator = 'AND')
	{
		return $this->match($field, $value, $operator, $match);
	}
	
	public function followRelation($name, $direction = 'out')
	{
		$this->relation .= '.'.$direction. '('.$this->registerParam($name). ')';
		return $this;
	}
	
	public function sort($field)
	{
		$this->sort .= '.sort{it.'.$field.'}';
		return $this;
	}
	
	public function limit($results, $offset = NULL)
	{
		if($offset !== NULL)
			$this->limitOffset = $offset;
		
		if($this->limitOffset !== 0)
			$results += $this->limitOffset;
		
		$this->limitCount = $results;
		
		return $this;
	}
	
	public function startNode($id)
	{
		$this->startNode = $id;
	}
	
	protected function parseFilter()
	{
		return '.filter{'.$this->filter.'}';
	}
	
	protected function parseIndex()
	{
		if(is_array($this->index))
		{
			$name	= $this->index['name'];
			$field	= $this->index['field'];
			$value	= $this->index['value'];
		}
		else
		{
			$name	= $this->index->getName();
			$conf	= $this->index->getConfig();
			$field	= $conf['indexField'];
			$value	= $conf['indexValue'];
		}
		
		return 'g.idx("'.$name.'")[['.$field.':"'.$value.'"]]';
	}
	
	protected function hasLimit()
	{
		return $this->limitCount !== null;
	}
	
	protected function parseLimit()
	{
		return '['.(int)$this->limitOffset.'..'.(int)$this->limitCount.']';
	}
	
	protected function parseId()
	{
		return 'g.v('.$this->registerParam($this->startNode).')';
	}
	
	protected function parseQuery()
	{
		$q = '';
		
		if($this->startNode !== null)
			$q .= $this->parseId ();
		
		if($this->index !== null)
			$q .= $this->parseIndex ();
		
		if($this->filter !== null)
			$q .= $this->parseFilter ();

		$q .= $this->relation;
		$q .= $this->sort;
		
		if($this->hasLimit())
			$q .= $this->parseLimit ();
		
		return $q;
	}
	
	public function toQuery($client)
	{
		return new Everyman\Neo4j\Gremlin\Query($client, $this->parseQuery(), $this->param);
	}
	
	public function toArray()
	{
		$fields = array(
			'prefix',
		);
		
		$result = array();
		
		foreach($fields as $v)
		{
			$result[$v] = $this->$v;
		}
		
		return $result;
	}
}

