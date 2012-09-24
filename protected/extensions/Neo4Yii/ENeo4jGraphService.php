<?php
class ENeo4jGraphService extends EActiveResourceConnection
{      
    
    public $host='localhost';
    public $port='7474';
    public $db='db/data';
    public $contentType="application/json";
    public $acceptType="application/json";
    public $allowNullValues=false; //neo4j doesn't allow null values
        
    public function init()
    {
        parent::init();
        $this->site=$this->host.':'.$this->port.'/'.$this->db;
    }

    /**
     * Creates an ENeo4jBatchTransaction used with this connection
     * @return ENeo4jBatchTransaction the transaction object
     */
    public function createBatchTransaction()
    {
        return new ENeo4jBatchTransaction($this);
    }

    /**
     * Query the neo4j instance with a gremlin script.
     * @param EGremlinScript the gremlin script to be sent
     * @return EActiveResourceResponse A response object holding the response of the neo4j instance.
     */
    public function queryByGremlin(EGremlinScript $gremlin)
    {
        Yii::trace(get_class($this).'.queryByGremlin()','ext.Neo4Yii.ENeo4jGraphService');
        $request=new EActiveResourceRequest;
        $request->setUri($this->site.'/ext/GremlinPlugin/graphdb/execute_script');
        $request->setMethod('POST');
        $request->setData(array('script'=>$gremlin->toString(),'params'=>$gremlin->getParams()));
        $response=$this->query($request);

        return $response;
    }
    
    /**
     * Creates a node index of given name with optional configuration
     * @param string $name The name of the index
     * @param array $config Optional index configuration used for creation
     */
    public function createNodeIndex($name,$config=array())
    {
        Yii::trace(get_class($this).'.createNodeIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $request=new EActiveResourceRequest;
        $request->setUri($this->site.'/index/node');
        $request->setMethod('POST');
        empty($config) ? $request->setData(array('name'=>$name)) : $request->setData(array('name'=>$name,'config'=>$config));
        $this->execute($request);        
    }
    
    /**
     * Creates a relationship index of given name with optional configuration
     * @param string $name The name of the index
     * @param array $config Optional index configuration used for creation
     */
    public function createRelationshipIndex($name,$config=array())
    {
        Yii::trace(get_class($this).'.createRelationshipIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $request=new EActiveResourceRequest;
        $request->setUri($this->site.'/index/relationship');
        $request->setMethod('POST');
        empty($config) ? $request->setData(array('name'=>$name)) : $request->setData(array('name'=>$name,'config'=>$config));
        $this->execute($request);        
    }
    
    /**
     * Delete a node index of the given name
     * @param string $name
     */
    public function deleteNodeIndex($name)
    {
        Yii::trace(get_class($this).'.deleteNodeIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $request=new EActiveResourceRequest;
        $request->setUri($this->site.'/index/node/'.urlencode($name));
        $request->setMethod('DELETE');
        $this->execute($request);        
    }
    
    /**
     * Delete a relationship index of the given name
     * @param string $name
     */
    public function deleteRelationshipIndex($name)
    {
        Yii::trace(get_class($this).'.deleteRelationshipIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $request=new EActiveResourceRequest;
        $request->setUri($this->site.'/index/relationship/'.urlencode($name));
        $request->setMethod('DELETE');
        $this->execute($request);        
    }
    
    /**
     * Add a node to an index. If no attributes are provided all attributes will be indexed
     * @param integer $nodeId The id of the node to be indexed.
     * @param array $attributes The Attributes to be indexed as key=>value pairs
     * @param string $index The name of the index to be used.
     * @param boolean $update Wheter to overwrite existing entries or not, defaults to false.
     */
    public function addNodeToIndex($nodeId,$attributes,$index,$update=false)
    {
        Yii::trace(get_class($this).'.addNodeToIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $trans=$this->createBatchTransaction();
        $trans->indexNode($nodeId,$attributes,$index,$update);
        $trans->execute();       
    }
    
    /**
     * Remove a node entry from an index
     * @param integer $nodeId The id of the node to be removed
     * @param array $attributes Optional: Attributes to be removed. Defaults to empty array meaning all attributes of this node will be removed
     * @param string $index Name of the index to be used
     */
    public function removeNodeFromIndex($nodeId,$index,$attributes=array())
    {
        Yii::trace(get_class($this).'.removeNodeFromIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $trans=$this->createBatchTransaction();
        $trans->removeNodeFromIndex($nodeId,$index,$attributes);
        $trans->execute();    
    }
    
    /**
     * Add a relationship to an index. If no attributes are provided all attributes will be indexed
     * @param integer $relationshipId The id of the node to be indexed.
     * @param array $attributes The Attributes to be indexed as key=>value pairs
     * @param string $index The name of the index to be used.
     * @param boolean $update Wheter to overwrite existing entries or not, defaults to false.
     */
    public function addRelationshipToIndex($relationshipId,$attributes,$index,$update=false)
    {
        Yii::trace(get_class($this).'.addRelationshipToIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $trans=$this->createBatchTransaction();
        $trans->indexRelationship($relationshipId,$attributes,$index,$update);
        $trans->execute();       
    }
    
    /**
     * Remove a relationship entry from an index
     * @param integer $relationshipId The id of the node to be removed
     * @param array $attributes Optional: Attributes to be removed. Defaults to empty array meaning all attributes of this relationship will be removed
     * @param string $index Name of the index to be used
     */
    public function removeRelationshipFromIndex($relationshipId,$index,$attributes=array())
    {
        Yii::trace(get_class($this).'.removeRelationshipFromIndex()','ext.Neo4Yii.ENeo4jGraphService');
        $trans=$this->createBatchTransaction();
        $trans->removeRelationshipFromIndex($relationshipId,$index,$attributes);
        $trans->execute();    
    }    
}
?>
