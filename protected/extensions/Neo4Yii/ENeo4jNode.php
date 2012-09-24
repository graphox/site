<?php
/**
 * @author Johannes "Haensel" Bauer
 * @since version 0.1
 * @version 0.1
 */

/**
 * ENeo4jNode represents a node in an Neo4j graph database. Every node is automatically indexed by default
 * This is especially important as we need an attribute (by default:modelclass) to determine how to instantiate
 * the node.
 */
class ENeo4jNode extends ENeo4jPropertyContainer
{
    const HAS_MANY='HAS_MANY';
    const HAS_ONE='HAS_ONE';
    const NODE='ENeo4jNode';
    const RELATIONSHIP='ENeo4jRelationship';
    const PATH='ENeo4jPath';

    private $_traversed=array();
    protected static $_models=array();

    /**
     * Inits the model and sets the modelclassfield so that the model can be instantiated properly.
     */
    public function init()
    {
        $modelclassfield=$this->getModelClassField();
        $this->$modelclassfield=get_class($this);
    }
    
    public function __get($name)
    {
        if(isset($this->attributes[$name]))
            return $this->attributes[$name];
        else if(isset($this->getMetaData()->properties[$name]))
            return null;
        else if(isset($this->_traversed[$name]))
            return $this->_traversed[$name];
        else if(isset($this->getMetaData()->traversals[$name]))
            return $this->getTraversed($name);
        else
            return parent::__get($name);
    }

    public static function model($className=__CLASS__)
    {
        if(isset(self::$_models[$className]))
            return self::$_models[$className];
        else
        {
            $model=self::$_models[$className]=new $className(null);
            $model->attachBehaviors($model->behaviors());
            return $model;
        }
    }

    public function rest()
    {
        return CMap::mergeArray(
            parent::rest(),
            array('resource'=>'node')
        );
    }

    public function routes()
    {
        return CMap::mergeArray(
            parent::routes(),
            array(
                'relationships'=>':site/:resource/:id/relationships'
            )
        );
    }

    /**
     * Returns the root node
     * @return ENeo4jNode The root node
     */
    public function getRoot()
    {
        Yii::trace('ENeo4jNode.getRoot()','ext.Neo4Yii.ENeo4jNode');
        $gremlinQuery=new EGremlinScript;
        $gremlinQuery->setQuery('g.v(0)');
        return ENeo4jNode::model()->populateRecord($this->getConnection()->queryByGremlin($gremlinQuery)->getData());
    }

    /**
     * Define simple traversals with the current node as starting point like this
     * 'traversalName'=>array(self::[HAS_ONE|HAS_MANY],self::[NODE|RELATIONSHIP],'out.in.filter{it.name=="A property value"}')
     * where HAS_ONE expects a single object to be returned while HAS_MANY expects an array of objects ro be returned.
     * Define the expected returntype via NODE or RELATIONSHIP. The third parameter is a gremlin script that will be added to "g.v(currentNodeId)." to only allow traversals
     * with the current node as a starting point.
     * @return array An array with the defined traversal configurations
     */
    public function traversals()
    {
        return array();
    }

    protected function getTraversed($name,$refresh=false)
    {
        if(!$refresh && (isset($this->_traversed[$name]) || array_key_exists($name,$this->_traversed)))
            return $this->_traversed[$name];

        $traversals=$this->getMetaData()->traversals;

        if(!isset($traversals[$name]))
            throw new ENeo4jException(Yii::t('yii','{class} does not have traversal definition "{name}".',
                array('{class}'=>get_class($this), '{name}'=>$name)));

        Yii::trace('lazy loading '.get_class($this).'.'.$name,'ext.Neo4Yii.ENeo4jNode');
        $traversal=$traversals[$name];
        if($this->getIsNewResource() && !$refresh)
            return $traversal[0]==self::HAS_ONE ? null : array();

        unset($this->_traversed[$name]);

        $query=new EGremlinScript;
        $query->setQuery('g.v(startNode).'.$traversal[2]);
        $query->setParam('startNode', $this->id);

        $resultData=$this->query($query)->getData();

        $class=$traversal[1];

        //if this is a path do not use model()
        if($class==self::PATH)
        {
            if($traversal[0]==self::HAS_ONE && isset($resultData[0]))
                $this->_traversed[$name]=ENeo4jPath::populatePath($resultData[0]);
            if($traversal[0]==self::HAS_MANY && isset($resultData[0]) && is_array($resultData[0]))
                $this->_traversed[$name]=ENeo4jPath::populatePaths($resultData);
        }
        else
        {
            if($traversal[0]==self::HAS_ONE && isset($resultData[0]))
                $this->_traversed[$name]=$class::model()->populateRecord($resultData[0]);
            if($traversal[0]==self::HAS_MANY && isset($resultData[0]) && is_array($resultData[0]))
            {
                Yii::trace($resultData[0],'TEST');
                $this->_traversed[$name]=$class::model()->populateRecords($resultData);
            }
        }
        if(!isset($this->_traversed[$name]))
        {
            if($traversal[0]==self::HAS_MANY)
                $this->_traversed[$name]=array();
            else
                $this->_traversed[$name]=null;
        }

        return $this->_traversed[$name];
    }
    
    /**
     * Populates traversed property containers. This method adds a traversed propertycontainer to this node.
     * @param string $name attribute name
     * @param mixed $propertyContainer the traversed property container
     * @param mixed $index the index value in the traversed object collection.
     * If true, it means using zero-based integer index.
     * If false, it means a HAS_ONE object and no index is needed.
     */
    public function addTraversedPropertyContainer($name,$propertyContainer,$index)
    {
        if($index!==false)
        {
            if(!isset($this->_traversed[$name]))
                $this->_traversed[$name]=array();
            if($propertyContainer instanceof ENeo4jPropertyContainer)
            {
                if($index===true)
                    $this->_traversed[$name][]=$propertyContainer;
                else
                    $this->_traversed[$name][$index]=$propertyContainer;
            }
        }
        else if(!isset($this->_traversed[$name]))
            $this->_traversed[$name]=$propertyContainer;
    }

    /**
     * Finds a single property container with the specified id within the modelclass index.
     * @param mixed $id The id.
     * @return ENeo4jPropertyContainer the property container found. Null if none is found.
     */
    public function findById($id)
    {
        if($id===null)
            throw new ENeo4jException ('Id missing!', 500);

        Yii::trace(get_class($this).'.findById()','ext.Neo4Yii.ENeo4jNode');
        $gremlinQuery=new EGremlinScript;

        $gremlinQuery->setQuery('g.v(startNode)._().filter{it.'.$this->getModelClassField().'=="'.get_class($this).'"}');
        $gremlinQuery->setParam('startNode', (int)$id);
        $responseData=$this->query($gremlinQuery)->getData();

        if(isset($responseData[0]))
            return self::model()->populateRecord($responseData[0]);
    }

    /**
     * Returns gremlin filter syntax based on given attribute key/value pair
     * @param array $attributes
     * @return string the resulting filter string
     */
    private function getFilterByAttributes(&$attributes)
    {
        Yii::trace(get_class($this).'.getFilterByAttributes()','ext.Neo4Yii.ENeo4jNode');
        $filter = "";
        foreach($attributes as $key=>$value) {
            if(!is_int($value)) {
                $value = '"' . $value . '"';
            }
            $filter .= ".filter{it.$key == $value}";
        }

        return (empty($filter) ? false : $filter);
    }

    /**
     * Find a single property container with the specified attributes within the modelclass index.
     * @param type $attributes
     * @return type
     */
    public function findByAttributes($attributes)
    {
        Yii::trace(get_class($this).'.findByAttributes()','ext.Neo4Yii.ENeo4jNode');
        $gremlinQuery=new EGremlinScript;

        $gremlinQuery->setQuery('g.V' . $this->getFilterByAttributes($attributes) .
            '.filter{it.'.$this->getModelClassField().'=="'.get_class($this).'"}[0]');
        $responseData=$this->query($gremlinQuery)->getData();

        if(isset($responseData[0]))
            return self::model()->populateRecord($responseData[0]);
        else
            return null;
    }

    /**
     * Find all models of the named class via gremlin query
     * @param type $attributes
     * @param array An array of model objects, empty if none are found
     */
    public function findAllByAttributes($attributes)
    {
        Yii::trace(get_class($this).'.findAllByAttributes()','ext.Neo4Yii.ENeo4jNode');
        $gremlinQuery=new EGremlinScript;

        $gremlinQuery->setQuery('g.V' . $this->getFilterByAttributes($attributes) .
            '.filter{it.'.$this->getModelClassField().'=="'.get_class($this).'"}');
        $responseData=$this->query($gremlinQuery)->getData();

        return self::model()->populateRecords($responseData);
    }
    
    /**
     * Finds a single node exactly matching the supplied key=>value pair. If no index name is supplied the index defined
     * via ENeo4jPropertyContainer::indexName() will be used which matches the classname of the node.
     * @param string $key The key
     * @param string $value The value
     * @param string $index Optional index name. If null the default index will be used
     * @return ENeo4jNode The resulting node, or null if none was found 
     */
    public function findByExactIndexEntry($key,$value,$index=null)
    {
        Yii::trace(get_class($this).'.findByExactIndexEntry()','ext.Neo4Yii.ENeo4jNode');
        if(is_null($index))
            $index=$this->indexName();
        $query=new EGremlinScript;
        $query->setQuery(
                'import org.neo4j.graphdb.index.*
                import org.neo4j.graphdb.*
                neo4j = g.getRawGraph()
                idxManager = neo4j.index()
                index = idxManager.forNodes("'.$index.'")
                ArrayList<Node> results = new ArrayList<Node>();
                int count = 0;
                for ( Node node : index.query("'.$this->getModelClassField().':'.get_class($this).' AND '.$key.':'.$value.'") )
                {
                    count++;
                    results.add( node );
                    if ( count >= 1 ) break;
                }
                return results[0];'
                );
        $responseData=$this->query($query)->getData();
        
        return ENeo4jNode::model()->populateRecord($responseData);
    }
    
    /**
     * Finds all nodes exactly matching the supplied key=>value pair. If no index name is supplied the index defined
     * via ENeo4jPropertyContainer::indexName() will be used which matches the classname of the node.
     * @param string $key The key
     * @param string $value The value
     * @param int $limit Limit the number of returned results. Defaults to 20
     * @param string $index Optional index name. If null the default index will be used
     * @return array An array of nodes, or an empty array if none were found 
     */
    public function findAllByExactIndexEntry($key,$value,$limit=20,$index=null)
    {
        Yii::trace(get_class($this).'.findAllByExactIndexEntry()','ext.Neo4Yii.ENeo4jNode');
        if(is_null($index))
            $index=$this->indexName();
        
        $query=new EGremlinScript;
        $query->setQuery(
                'import org.neo4j.graphdb.index.*
                import org.neo4j.graphdb.*
                neo4j = g.getRawGraph()
                idxManager = neo4j.index()
                index = idxManager.forNodes("'.$index.'")
                ArrayList<Node> results = new ArrayList<Node>();
                int count = 0;
                for ( Node node : index.query("'.$this->getModelClassField().':'.get_class($this).' AND '.$key.':'.$value.'") )
                {
                    count++;
                    results.add( node );
                    if ( count >= '.(int)$limit.' ) break;
                }
                return results;
             ');
        $responseData=$this->query($query)->getData();
        
        return ENeo4jNode::model()->populateRecords($responseData);
    }
    
    
    /**
     * Finds nodes according to a lucene query
     * @param string $indexQuery The query
     * @param int $limit The number of results to be returned
     * @param string $index Optional name of the index to be used for searching. Defaults to the index defined via
     * indexName()
     * @return array An array of resulting nodes or empty array if no results were found
     */
    public function findByIndexQuery($indexQuery,$limit=20,$index=null)
    {
        Yii::trace(get_class($this).'.findByIndexQuery()','ext.Neo4Yii.ENeo4jNode');
        if(is_null($index))
            $index=$this->indexName();
        
        $query=new EGremlinScript;
        $query->setQuery(
                'import org.neo4j.graphdb.index.*
                import org.neo4j.graphdb.*
                import org.neo4j.index.lucene.*
                import org.apache.lucene.search.*
                neo4j = g.getRawGraph()
                idxManager = neo4j.index()
                index = idxManager.forNodes("'.$index.'")
                query = new QueryContext("'.$indexQuery.'").top('.$limit.')
                results = index.query(query)');
        
        $responseData=$this->query($query)->getData();
        
        return ENeo4jNode::model()->populateRecords($responseData);
    }
    
    /**
     * Find all models of the named class via custom gremlin query
     * @param type $query
     */
    public function findAllByQuery($query)
    {
        Yii::trace(get_class($this).'.findAllByQuery()','ext.Neo4Yii.ENeo4jNode');
        $gremlinQuery=new EGremlinScript;

        $gremlinQuery->setQuery($query. '.filter{it.'.$this->getModelClassField().'=="'.get_class($this).'"}');
        $responseData=$this->query($gremlinQuery)->getData();

        return self::model()->populateRecords($responseData);
    }

    /**
     * Finds all models of the named class via an index query on the modelclass attribute
     * @param int $limit Limit the number of results. Defaults to 100
     * @return array An array of model objects, empty if none are found
     */
    public function findAll($limit=100)
    {
        Yii::trace(get_class($this).'.findAll()','ext.Neo4Yii.ENeo4jNode');
        
        /* DEPRECATED WAY: Iterate over all nodes, filtering by modelclass attribute
        $gremlinQuery=new EGremlinScript;

        $gremlinQuery->setQuery('g.V._().filter{it.'.$this->getModelClassField().'=="'.get_class($this).'"}');
        $responseData=$this->query($gremlinQuery)->getData();
        */
        return $this->findAllByExactIndexEntry($this->getModelClassField(),get_class($this),$limit,$this->indexName());
    }

    /**
     * Returns an array of incoming relationships. You can specify which relationships you want by adding
     * them with a ',' seperator. e.g.: incoming('HAS_ATTRIBUTE,'HAS_NAME'')
     * If no type is pecified all incoming relationships will be returned
     * @param string $types The types you want to get
     * @return array An array of ENeo4jRelationship objects
     */
    public function inRelationships($types=null)
    {
        return $this->getRelationships($types,$direction='in');
    }

    /**
     * Returns an array of outgoing relationships. You can specify which relationships you want by adding
     * them with a ',' seperator. e.g.: outgoing('HAS_ATTRIBUTE,'HAS_NAME'')
     * If no type is specified all outgoing relationships will be returned
     * @param string $types The types you want to get
     * @return array An array of ENeo4jRelationship objects
     */
    public function outRelationships($types=null)
    {
        return $this->getRelationships($types,$direction='out');

    }

    /**
     * Returns an array of relationships. You can specify which relationships you want by adding
     * them with a ',' seperator. e.g.: incoming('HAS_ATTRIBUTE,'HAS_NAME'')
     * If no type is pecified all relationships will be returned
     * You can also define the direction of the relationships by using one of the three options for direction ('all','in','out')
     * @param string $types The types you want to get
     * @param string $direction The direction of the relationships
     * @return array An array of ENeo4jRelationship objects
     */
    public function getRelationships($types=null,$direction='all')
    {
        Yii::trace(get_class($this).'.getRelationships()','ext.Neo4Yii.ENeo4jNode');
        $uri=$this->getSite().'/'.$this->getResource().'/'.$this->getId().'/relationships';
        if($direction)
            $uri.='/'.$direction;
        if($types)
        {
            if(is_array($types))
            {
                $types=implode('&',$types);
                $uri.='/'.$types;
            }
            else
                $uri.='/'.$types;
        }

        $response=$this->getRequest($uri);

        if($response->hasErrors())
            $response->throwError();

        return ENeo4jRelationship::model()->populateRecords($response->getData());
    }

    /**
     * Add a relationship to another node
     * @param mixed $node Either a node object to connect with or a node id which represents the endNode of this relationship
     * @param string $type The type of this relationship. something like 'HAS_NAME'. If this is the name of an existing relationship class this class will be instantiated, if not ENeo4jRelationship will be used
     * @param array $properties An array of properties used for the relationship. e.g.: array('since'=>'2010')
     *
     * @return ENeo4jRelationship
     */
    public function addRelationshipTo($node, $type, array $properties=null)
    {
        Yii::trace(
            get_class($this).'.addRelationshipTo()',
            'ext.Neo4Yii.ENeo4jNode'
        );

        $relationship = new $type;
        if (!$relationship instanceof ENeo4jRelationship)
            throw new ENeo4jException(
                'Class is not an instance of ENeo4jRelationship', 500
            );

        $relationship->setAttributes($properties);
        $relationship->setStartNode($this);
        $relationship->setEndNode($node);
        $relationship->save();

        return $relationship;
    }
}

