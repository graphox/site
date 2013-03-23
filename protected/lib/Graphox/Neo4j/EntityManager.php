<?php

/**
 * @package Graphox\Neo4j
 * @author killme
 * @todo use pimple
 * @ignore
 */

namespace Graphox\Neo4j;

use \Yii;

/**
 * @ignore
 */
class EntityManager extends \CApplicationComponent
{
	private $config;

	/**
	 *
	 * @staticvar \HireVoice\Neo4j\EntityManager $manager
	 * @return \HireVoice\Neo4j\EntityManager entity manager
	 */
	private function getManager()
	{
		static $manager;

		if(!isset($manager))
		{
			$manager = new \HireVoice\Neo4j\EntityManager($this->config);
			$this->config = false;
		}

		return $manager;
	}

	public function setTransport($name)
	{
		$this->config['transport'] = $name;
	}

	public function setHost($name)
	{
		$this->config['host'] = $name;
	}

	public function setPort($port)
	{
		$this->config['port'] = $port;
	}

	public function setProxyDir($dir)
	{
		$this->config['proxy_dir'] = Yii::getPathOfAlias($dir);
	}

	public function setDebug($debug)
	{
		$this->config['debug'] = $debug;
	}

	public function setUsername($user)
	{
		$this->config['username'] = $user;
	}

	public function setPassword($pass)
	{
		$this->config['password'] = $pass;
	}

	public function addAnnotationRegistry($namespace, $directory)
	{
		\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
			$namespace,
			$directory
		);
	}

	public function __construct()
	{

		$this->addAnnotationRegistry('HireVoice\Neo4j\Annotation', Yii::getPathOfAlias('application.vendor.hirevoice.neo4jphp-ogm.lib'));
	}

	/**
     * Includes an entity to persist on the next flush. Persisting entities will cause
     * relations to be followed to discover other entities. Relation traversal will happen
     * during the flush.
     *
     * @param object $entity Any object providing the correct Entity annotations.
     */
    public function persist($entity)
    {
        $this->getManager()->persist($entity);
		return $this;
    }

    function remove($entity)
    {
        $this->getManager()->remove($entity);
		return $this;
    }


    /**
     * Commit changes in the object model into the database. Relations will be traversed
     * to discover additional entities. To include an object in the unit of work, use the
     * persist() method.
     */
    function flush()
    {
		$this->getManager()->flush();
		return $this;
    }

    /**
     * Searches a single entity by ID for a given class name. The result will be provided
     * as a proxy node to handle lazy loading of relations.
     *
     * @param string $class The fully qualified class name
     * @param int $id The node ID
     * @return object The entity object
     */
    function find($class, $id)
    {
        return $this->getManager()->find($class, $id);
    }

    /**
     * Searches a single entity by ID, regardless of the class used. The result will be
     * provided as a proxy
     *
     * @param int $id The node ID
     */
    function findAny($id)
    {
		return $this->getManager()->findAny($id);
    }

    /**
     * Reload an entity. Exchanges an raw entity or an invalid proxy with an initialized
     * proxy.
     *
     * @param object $entity Any entity or entity proxy
     */
    function reload($entity)
    {
		return $this->getManager()->reload();
    }

    /**
     * Clear entity cache.
     */
    function clear()
    {
        $this->getManager()->clear();
		return $this;
    }

    /**
     * Provide a Gremlin query builder.
     *
     * @param string $query Initial query fragment.
     * @return Query\Gremlin
     */
    function createGremlinQuery($query = null)
    {
		return $this->getManager()->createGremlinQuery($query);
    }

    /**
     * Raw gremlin query execution. Used by Query\Gremlin.
     *
     * @param string $string The query string.
     * @param array $parameters The arguments to bind with the query.
     * @return Everyman\Neo4j\Query\ResultSet
     */
    function gremlinQuery($string, $parameters)
    {
        return $this->getManager()->gremlinQuery($string, $parameters);
    }

    /**
     * Provide a Cypher query builder.
     *
     * @return Query\Cypher
     */
    function createCypherQuery()
    {
        return $this->getManager()->createCypherQuery();
    }

    /**
     * Raw cypher query execution.
     *
     * @param string $string The query string.
     * @param array $parameters The arguments to bind with the query.
     * @return Everyman\Neo4j\Query\ResultSet
     */
    function cypherQuery($string, $parameters)
    {
        return $this->getManager()->cypherQuery($string, $parameters);
    }

    /**
     * Obtain an entity repository for a single class. The repository provides
     * multiple methods to access nodes and can be extended per entity by
     * specifying the correct annotation.
     *
     * @param string $class Fully qualified class name
     */
    function getRepository($class)
    {
		return $this->getManager()->getRepository($class);
    }

    /**
     * Register an event listener for a given event.
     *
     * @param string $eventName The event to listen, available as constants.
     */
    function registerEvent($eventName, $callback)
    {
		$this->getManager()->getRepository($eventName, $callback);
        return $this;
    }

    function createIndex($className)
    {
        return $this->getManager()->createIndex($className);
    }

    /**
     * Alter how dates are generated. Primarily used for test cases.
     */
    function setDateGenerator(\Closure $generator)
    {
		$this->getManager()->setDateGenerator($generator);
		return $this;
    }

    /**
     * Returns the Client
     *
     * @return Everyman\Neo4j\Client
     */
    public function getClient()
    {
        return $this->getManager()->client;
    }

    public function getPathFinder()
    {
        return clone $this->getManager()->pathFinder;
	}
}