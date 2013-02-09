<?php

class Neo4jClient extends Everyman\Neo4j\Client
{
	public function __construct()
	{
		$this->setNodeFactory(function (Neo4jClient $client, $properties=array()) {
			return new Everyman\Neo4j\Node($client);
		});
		$this->setRelationshipFactory(function (Neo4jClient $client, $properties=array()) {
			return new Everyman\Neo4j\Relationship($client);
		});
		

	}
	
	/**
	 * config file wrapper
	 * @param type $var
	 * @param type $value
	 */
	public function __set($var, $value)
	{
		switch($var)
		{
			case 'transport':
				$transport = null;
				try {
					$transport = new Everyman\Neo4j\Transport\Curl(
						isset($value['host'])? $value['host']: 'localhost',
						isset($value['port'])? $value['port']: 7474
					);

				} catch (Exception $e) {
					$transport = new Everyman\Neo4j\Transport\Stream(
						isset($value['host'])? $value['host']: 'localhost',
						isset($value['port'])? $value['port']: 7474
					);
				}

				$this->setTransport($transport);
		
				break;
		}
	}
	
	public function init()
	{
		
	}
	
	/**
	 * Get the entity mapper
	 *
	 * @return EntityMapper
	 */
	public function getEntityMapper()
	{
		if ($this->entityMapper === null)
		{
			$this->setEntityMapper(new Neo4jEntityMapper($this));
		}
		
		return $this->entityMapper;
	}
}

