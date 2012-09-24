<?php
/**
 * @author Johannes "Haensel" Bauer
 * @since version 0.1
 * @version 0.1
 */

/**
 * ENeo4jRelationship represents a relationship in an Neo4j graph database
 */
class ENeo4jRelationship extends ENeo4jPropertyContainer
{
    private $_startNode; //a container for the startNode object or the startNode id
    private $_endNode; //a container for the endNode object or the endNode id
    private $_type;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Sets the type according to the classname
     */
    public function init()
    {
        parent::init();
        $this->_type=get_class($this);
    }

    /**
     * Returns the type of this relationship
     * @return string The type of this relationship
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get information of the PropertyContainer class and extend by adding the relationship resource
     * @return <type>
     */
    public function rest()
    {
        return CMap::mergeArray(
            parent::rest(),
            array('resource'=>'relationship')
        );
    }

    /**
     * Relationships are created differently to nodes, so we override the ActiveResource method here.
     * @param array $attributes The attributes to be used when creating the relationship
     * @return boolean true on success, false on failure
     */
    public function create($attributes=null)
    {
        if(!$this->getIsNewResource())
            throw new ENeo4jException('The relationship cannot be inserted because it is not new.',500);

        //check if one of the vital infos isn't there
        if($this->_endNode==null || $this->_type==null || $this->_startNode==null)
            throw new ENeo4jException('You cannot save a relationship without defining type, startNode and endNode',500);

        $startNodeId=$this->_startNode instanceOf ENeo4jNode ? $this->_startNode->id : $this->_startNode;
        $endNodeId=$this->_endNode instanceOf ENeo4jNode ? $this->_endNode->id : $this->_endNode;
        
        if($this->beforeSave())
        {
            Yii::trace(get_class($this).'.create()','ext.Neo4Yii.ENeo4jRelationship');
            
            $attributesToSend=$this->getAttributesToSend($attributes);
            
            if(!empty($attributesToSend))
            {
                $response=$this->postRequest($this->getSite().'/node/'.$startNodeId.'/relationships',array(),array(
                    'to'=>$this->getSite().'/'.$endNodeId,
                    'type'=>$this->_type,
                    'data'=>$attributesToSend
                ));
            }
            else
            {
                $response=$this->postRequest($this->getSite().'/node/'.$startNodeId.'/relationships',array(),array(
                    'to'=>$this->getSite().'/'.$endNodeId,
                    'type'=>$this->_type,
                ));
            }

            $responseData=$response->getData();

            $returnedmodel=$this->populateRecord($response->getData());

            if($returnedmodel)
            {
                $id=$this->idProperty();
                $this->$id=$returnedmodel->getId();
            }

            $this->afterSave();
            $this->setIsNewResource(false);
            $this->setScenario('update');
            return true;

        }
        return false;
    }

    /**
     * Finds a single relationship with the specified id.
     * @param mixed $id The id.
     * @return ENeo4jRelationship the relationship found. Null if none is found.
     */
    public function findById($id)
    {
        Yii::trace(get_class($this).'.findById()','ext.Neo4Yii.ENeo4jRelationship');
        $gremlinQuery=new EGremlinScript;
        $gremlinQuery->setQuery('g.e(startEdge)._().filter{it.getLabel()==label}');
        $gremlinQuery->setParam('startEdge',(int)$id);
        $gremlinQuery->setParam('label',get_class($this));
        $response=$this->query($gremlinQuery);
        $responseData=$response->getData();
        if(isset($responseData[0]))
        {
            $model=$this->populateRecords($responseData);
            return $model[0];
        }
        else
            return null;
    }

    /**
     * Finds all models of the named class via a gremlin iterator g.E filtering on the modelclass attribute
     * @param int $limit Limit the number of results. Defaults to 100
     * @return array An array of model objects, empty if none are found
     */
    public function findAll($limit=100)
    {
        Yii::trace(get_class($this).'.findAll()','ext.Neo4Yii.ENeo4jRelationship');
        
        $gremlinQuery=new EGremlinScript;
        if(is_integer($limit))
            $gremlinQuery->setQuery('g.E._().filter{it.getLabel()=="'.get_class($this).'"}[0..'.$limit.']');
        else
            $gremlinQuery->setQuery('g.E._().filter{it.getLabel()=="'.get_class($this).'"}');
        if (__CLASS__ === get_class($this)) {
            $gremlinQuery->setQuery('g.E._()');
        }
        $responseData=$this->query($gremlinQuery)->getData();

        return self::model()->populateRecords($responseData);        
    }

    /**
     * Setter for the startNode
     * @param mixed $node Either a node id or a ENeo4jNode model
     */
    public function setStartNode($node)
    {
        $this->_startNode=$node;
    }

    /**
     * Setter for the endNode
     * @param mixed $node Either a node id or a ENeo4jNode model
     */
    public function setEndNode($node)
    {
        $this->_endNode=$node;
    }

    /**
     * Get the start node object
     * @return ENeo4jNode The node
     */
    public function getStartNode()
    {
        if(isset($this->_startNode) && $this->_startNode instanceof ENeo4jNode)
            return $this->_startNode;
        else
        {
            Yii::trace(get_class($this).' is lazyLoading startNode','ext.Neo4Yii.ENeo4jRelationship');
            $gremlinQuery=new EGremlinScript;
            $gremlinQuery->setQuery('g.e('.$this->getId().').outV');

            $responseData=$this->getConnection()->queryByGremlin($gremlinQuery)->getData();

            if(isset($responseData[0]))
                return $this->_startNode=ENeo4jNode::model()->populateRecord($responseData[0]);
        }
    }

    /**
     * Get the end node object
     * @return ENeo4jNode The node
     */
    public function getEndNode()
    {
        if(isset($this->_endNode) && $this->_endNode instanceof ENeo4jNode)
            return $this->_endNode;
        else
        {
            Yii::trace(get_class($this).' is lazyLoading endNode','ext.Neo4Yii.ENeo4jRelationship');
            $gremlinQuery=new EGremlinScript;
            $gremlinQuery->setQuery('g.e('.$this->getId().').inV');

            $responseData=$this->getConnection()->queryByGremlin($gremlinQuery)->getData();

            if(isset($responseData[0]))
                return $this->_endNode=ENeo4jNode::model()->populateRecord($responseData[0]);
        }
    }

    /**
     * Returns gremlin filter syntax based on given attribute key/value pair
     * @param array $attributes
     * @return string the resulting filter string
     */
    private function getFilterByAttributes(&$attributes)
    {
        Yii::trace(get_class($this).'.getFilterByAttributes()','ext.Neo4Yii.ENeo4jRelationship');
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
        Yii::trace(get_class($this).'.findByAttributes()','ext.Neo4Yii.ENeo4jRelationship');
        $gremlinQuery=new EGremlinScript;

        $gremlinQuery->setQuery('g.E' . $this->getFilterByAttributes($attributes) .
            '.filter{it.getLabel()=="'.get_class($this).'"}[0]');
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
        Yii::trace(get_class($this).'.findAllByAttributes()','ext.Neo4Yii.ENeo4jRelationship');
        $gremlinQuery=new EGremlinScript;

        $gremlinQuery->setQuery('g.E' . $this->getFilterByAttributes($attributes) .
            '.filter{it.getLabel()=="'.get_class($this).'"}');
        $responseData=$this->query($gremlinQuery)->getData();

        return self::model()->populateRecords($responseData);
    }
    
    /**
     * Finds a single relationship exactly matching the supplied key=>value pair. If no index name is supplied the index defined
     * via ENeo4jPropertyContainer::indexName() will be used which matches the classname of the node.
     * @param string $key The key
     * @param string $value The value
     * @param string $index Optional index name. If null the default index will be used
     * @return ENeo4jRelationship The resulting node, or null if none was found 
     */
    public function findByExactIndexEntry($key,$value,$index=null)
    {
        Yii::trace(get_class($this).'.findByExactIndexEntry()','ext.Neo4Yii.ENeo4jRelationship');
        if(is_null($index))
            $index=$this->indexName();
        $query=new EGremlinScript;
        $query->setQuery(
                'import org.neo4j.graphdb.index.*
                import org.neo4j.graphdb.*
                neo4j = g.getRawGraph()
                idxManager = neo4j.index()
                index = idxManager.forRelationships("'.$index.'")
                ArrayList<Relationship> results = new ArrayList<Relationship>();
                int count = 0;
                for ( Relationship rel : index.query("'.$this->getModelClassField().':'.get_class($this).' AND '.$key.':'.$value.'") )
                {
                    count++;
                    results.add( rel );
                    if ( count >= 1 ) break;
                }
                return results[0];'
                
                );
        $responseData=$this->query($query)->getData();
        
        return ENeo4jRelationship::model()->populateRecord($responseData);
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
        Yii::trace(get_class($this).'.findAllByExactIndexEntry()','ext.Neo4Yii.ENeo4jRelationship');
        if(is_null($index))
            $index=$this->indexName();
        
        $query=new EGremlinScript;
        $query->setQuery(
                'import org.neo4j.graphdb.index.*
                import org.neo4j.graphdb.*
                neo4j = g.getRawGraph()
                idxManager = neo4j.index()
                index = idxManager.forRelationships("'.$index.'")
                ArrayList<Relationship> results = new ArrayList<Relationship>();
                int count = 0;
                for ( Relationship rel : index.query("'.$this->getModelClassField().':'.get_class($this).' AND '.$key.':'.$value.'") )
                {
                    count++;
                    results.add( rel );
                    if ( count >= '.(int)$limit.' ) break;
                }
                return results;'
                );
        $responseData=$this->query($query)->getData();
        
        return ENeo4jRelationship::model()->populateRecords($responseData);
    }
    
    
    /**
     * Finds relationships according to a lucene query
     * @param string $indexQuery The query
     * @param int $limit Limit the number of results. Defaults to 20
     * @param string $index Optional name of the index to be used for searching. Defaults to the index defined via
     * indexName()
     * @return array An array of resulting nodes or empty array if no results were found
     */
    public function findByIndexQuery($indexQuery,$limit=20,$index=null)
    {
        Yii::trace(get_class($this).'.findByIndexQuery()','ext.Neo4Yii.ENeo4jRelationship');
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
                index = idxManager.forRelationships("'.$index.'")
                query = new QueryContext("'.$indexQuery.'").top('.(int)$limit.')
                results = index.query(query)');
        
        $responseData=$this->query($query)->getData();
        
        return ENeo4jRelationship::model()->populateRecords($responseData);
    }

}

