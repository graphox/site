<?php

class Neo4jNode extends Neo4jModel
{
	
	/**
	 * @var \Everyman\Neo4j\Node reference to node in database
	 */
	private $_n;
	
	private $_t;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function properties()
	{
		return array(
			$this->getClassField() => array( 'type' => 'string' , 'default' => $this->getClassName())
		);
	}

	public function __construct($scenario = Scenario::INSERT)
	{
		if($this->scenario !== Scenario::MODEL)
		{
			$this->_n = $this->getConnection()->makeNode();
		}
		
		parent::__construct($scenario);
	}
	
	public function getClassField()
	{
		return $this->getConnection()->getEntityMapper()->getClassField();
	}

	public function getClassName()
	{
		return $this->getConnection()->getEntityMapper()->getClassName($this);
	}
	
	public function setAttribute($property, $value)
	{
		$this->_n->setProperty($property, $value);
	}
	
	public function getAttribute($property)
	{
		return $this->_n->getProperty($property);
	}

	
	public function traversals()
	{
		return array(
			
		);
	}
	
	public function hasTraversal($name)
	{
		$t = $this->traversals();
		return isset($t[$name]);
	}
	
	public function getTraversal($name)
	{
		if(isset($this->_t[$name]))
			return $this->_t[$name];
		
		$t = $this->traversals();
		
		if(!isset($t[$name]))
			throw new CException('no such traversal '.$name);
		$traversal = $t[$name];
		
		$multi = (array_shift($traversal) !== self::HAS_ONE);		
		$class = array_shift($traversal);
		
		$crit = new Neo4jDbCriteria($traversal);
		$crit->startNode($this->id);
		
		if(!class_exists($class))
			throw new CException($name.' => '.$class);
		return $class::model()->{$multi ? 'queryAll' : 'query'}($crit);
	}
	
	public function __get($name)
	{
		if($this->propertyExist($name))
			return $this->getAttribute ($name);
		elseif($this->hasTraversal($name))
			return $this->getTraversal($name);
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
		
		return $this->_n->getId();
	}
	
	public function attributeNames()
	{
		return array_keys($this->properties());
	}

	protected function getIndex()
	{
		return new Neo4jNodeIndex($this, array(
			'indexField' => $this->getClassField(),
			'indexValue'	=> $this->getClassName(), 
		));
	}
	
	protected function defaultCriteria()
	{
		return array(
			'index' => $this->getIndex()
		);
	}
	
	protected function initCriteria()
	{
		$this->_criteria = new Neo4jDbCriteria(
			$this->defaultCriteria()
		);
	}
	
	public function findAllByQuery($q)
	{
		$r = $q->getResultSet();
		
		//broken index fix
		$class = get_class($this);
		
		$return = array();
		foreach($this->getConnection()->getEntityMapper()->getEntitiesFromResultSet($r) as $row)
			if($row instanceof $class)
				$return[$row->getId()] = $row;
			
		return $return;
	}
	
	public function queryAll($crit)
	{
		return $this->findAllByQuery(
			$crit->toQuery(
				$this->getConnection()
			)
		);
	}

	public function query($crit)
	{
		$crit->limit(1);
		$data = $this->queryAll($crit);
		
		return reset($data)?reset($data):null;
	}
	
	public function findByAttributes($attr)
	{
		$crit = clone $this->getCriteria();
		
		foreach($attr as $k => $v)
			$crit->match($k, $v);
		
		return $this->query($crit);
	}

	public function findAllByAttributes($attr)
	{
		$crit = clone $this->getCriteria();
		
		foreach($attr as $k => $v)
			$crit->match($k, $v);
		
		return $this->queryAll($crit);
	}

	public function findAll()
	{
		$crit = clone $this->getCriteria();
		
		
		return $this->queryAll($crit);
	}
	
	public function populateRecord($data, $id)
	{
		$this->_n->setId($id);
		parent::populateRecord($data, $id);
	}
}
