<?php

abstract class Neo4jModel extends CModel
{
	const HAS_MANY		=	'has_many';
	const HAS_ONE		=	'has_one';
	
	const DIRECTION_IN	=	'in';
	const DIRECTION_OUT	=	'out';
	const DIRECTION_ANY	=	'any';
	
	const NODE			=	'';
	const RELATIONSHIP	=	'';
	
	/**
	 * @var array an array containing all ::model() instances 
	 */
	private static $_m = array();
	
	/**
	 * @var DbCriteria the database query criteria of this model 
	 */
	protected $_criteria;

	/**
	 * @var bool true if the model has not been saved to the database yet 
	 */
	private $isNew;
	
	/**
	 * Create a new modelclass from $className
	 * @param string $className name of the class
	 * @return Neo4jNode
	 */
	public static function model($className = __CLASS__)
	{
		if(!isset($_m[$className]))
			$_m[$className] = new $className(Scenario::MODEL);
		
		return $_m[$className];
	}
	
	

	
	/**
	 * Initializes the class 
	 * @param string $scenario the name of the scenario to load
	 */
	public function __construct($scenario = Scenario::INSERT)
	{
		//is overriden by the instantiate function
		$this->isNew = true;
		
		if($scenario == Scenario::MODEL)
		{
			$this->initCriteria();
		}
		else
		{
			$this->initProperties();
		}
		
		$this->scenario = $scenario;
		
		$this->attachBehaviors($this->behaviors());
		$this->afterConstruct();
	}
	
	public function behaviors()
	{
		return array();
	}
	
	/**
	 * @return string the name of the connection in Yii::app()->$name
	 */
	public function connection()
	{
		return 'neo4j';
	}
	
	/**
	 * @return Neo4jClient
	 */
	public function getConnection()
	{
		return Yii::app()->{$this->connection()};
	}
	
		
	public function properties()
	{
		return array(
			
		);
	}
	
	public function initProperties()
	{
		foreach($this->properties() as $name => $row)
		{
			if(isset($row['default']))
			{
				$this->setAttribute($name, $row['default']);
			}
		}
	}
	
	public function propertyExist($name)
	{
		
		$props = $this->properties();
		
		return isset($props[$name]);
	}
	
	abstract public function setAttribute($property, $value);
	abstract public function getAttribute($property);
	abstract public function getId();
	abstract protected function initCriteria();
		
	public function hasAttribute($property)
	{
		return $this->getAttribute($property) !== null;
	}
		
	protected function getCriteria()
	{
		if(!isset($this->_criteria))
			$this->initCriteria();
		
		return $this->_criteria;
	}
	
	public function resetCriteria()
	{
		unset($this->_criteria);
		$this->initCriteria();
	}
	
		
	public function afterSave()
	{
		$this->isNew = false;
	}
	
		public function isNew()
	{
		return $this->isNew;
	}
	
	public function getIsNew()
	{
		return $this->isNew();
	}
	
	public function getIsNewRecord()
	{
		return $this->isNew();
	}
	
	public function isNewRecord()
	{
		return $this->isNew();
	}
	
	public function instantiate($data)
	{
		$self = get_class($this);
		$i = new $self(Scenario::UPDATE);
		
		return $i;		
	}
	
	public function populateRecord($id, $data)
	{
		$this->isNew = false;
		
		foreach($data['data'] as $k => $v)
			$this->setAttribute($k, $v);
	}
}

