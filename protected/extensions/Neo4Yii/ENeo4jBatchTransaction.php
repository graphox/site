<?php
/**
 * @author Johannes "Haensel" Bauer
 */

/**
 * ENeo4jBatchTransaction is used to use neo4j's batch api.
 */
class ENeo4jBatchTransaction
{
    private $_operations=array();
    
    private $_connection;
    
    public function __construct(EActiveResourceConnection $connection)
    {
        $this->_connection=$connection;
    }
    
    /**
     * Returns the graph service object
     * @return ENeo4jGraphService the graph service object 
     */
    public function getConnection()
    {
        return $this->_connection;
    }
    
    /**
     * Add a custom operation to the current transaction and get the batchId in return
     * @param string $method The http method to be used (GET,PUT,POST,DELETE)
     * @param string $to The uri this operation is using. e.g.: '/index/node'
     * @param array $body The body to be used by this operation
     * @return int The batch id used for this operation 
     */
    public function addOperation($method,$to,$body=array())
    {
        $batchId=count($this->_operations);
        $this->_operations[]=array(
                'method'=>$method,
                'to'=>$to,
                'id'=>$batchId,
                'body'=>$body);
        return $batchId;
    }
    
    /**
     * Get a single node
     * @param int $node The node id
     * @return int The batch id 
     */
    public function getNode($node)
    {
        return $this->addOperation('GET','node/'.$node);
    }
    
    /**
     * Get a single relationship
     * @param int $relationship The $relationship id
     * @return int The batch id 
     */
    public function getRelationship($relationship)
    {
        return $this->addOperation('GET','relationship/'.$relationship);
    }
    
    /**
     * Create a new node
     * @param array $attributes The attributes for this node
     * @return int The batch id 
     */
    public function createNode($attributes=array())
    {
        return $this->addOperation('POST','node',$attributes);
    }
    
    /**
     * Create a new relationship
     * @param array $attributes The attributes for this relationship
     * @return int The batch id 
     */
    public function createRelationship($fromNode,$toNode,$type,$attributes=array())
    {
        if(strpos($fromNode,'{')===false)
            $fromNode='node/'.$fromNode;
        if(strpos($toNode,'{')===false)
            $toNode='node/'.$toNode;
        if(empty($attributes))
            return $this->addOperation('POST',$fromNode.'/relationships',array('to'=>$toNode,'type'=>$type));
        else
            return $this->addOperation('POST',$fromNode.'/relationships',array('to'=>$toNode,'type'=>$type,'data'=>$attributes));
    }
    
    /**
     * Delete a node
     * @param int $node The node id
     * @return int The batch id 
     */
    public function deleteNode($node)
    {
        return $this->addOperation('DELETE','node/'.$node);
    }
    
    /**
     * Delete a relationship
     * @param int $relationship The relationship id
     * @return int The batch id 
     */
    public function deleteRelationship($relationship)
    {
        return $this->addOperation('DELETE','relationship/'.$relationship);
    }
            
    /**
     * Adds a node to a defined index with given attributes.
     * @param integer $nodeId The id of the node to be indexed.
     * @param array $attributes The attributes to be indexed
     * @param string $index The name of the index to be used
     * @param boolean $update Set to true if you want to replace existing index entries. Defaults to false, meaning adding index entries, even if they already exist
     */
    public function indexNode($nodeId,$attributes=array(),$index,$update=false)
    {
        if($update)
        {
            foreach($attributes as $key=>$value)
                $this->addOperation('DELETE', '/index/node/'.urlencode($index).'/'.urlencode($key).'/'.$nodeId);
        }
            
        foreach($attributes as $key=>$value)
            $this->addOperation('POST','/index/node/'.urlencode($index),array('uri'=>$this->getConnection()->site.'/'.$nodeId,'key'=>$key,'value'=>$value));
    }
    
    /**
     * Adds a relationship to a defined index with given attributes.
     * @param integer $relationshipId The id of the node to be indexed.
     * @param array $attributes The attributes to be indexed
     * @param string $index The name of the index to be used
     * @param boolean $update Set to true if you want to replace existing index entries. Defaults to false, meaning adding index entries, even if they already exist
     */
    public function indexRelationship($relationshipId,$attributes,$index,$update=false)
    {        
        if($update)
        {
            foreach($attributes as $key=>$value)
                $this->addOperation ('DELETE','/index/relationship/'.urlencode($index).'/'.urlencode($key).'/'.$relationshipId);
        }
            
        foreach($attributes as $key=>$value)
            $this->addOperation('POST', '/index/relationship/'.urlencode($index),array('uri'=>$this->getConnection()->site.'/'.$relationshipId,'key'=>$key,'value'=>$value));
    }
    
    /**
     * Removes a property container from an index. If attributes are supplied only entries for given attribute keys will be deleted
     * If no attributes are given, all entries for the given property container will be deleted.
     * @param integer $nodeId The id of the node to be removed from the index
     * @param string $index Name of the index this operation will be using
     * @param array $attributes Optional: An array of attributes to be deleted for this property container. If none are provided the node will be removed for all indexed properties
     */
    public function removeNodeFromIndex($nodeId,$index,$attributes=array())
    {        
        if(!empty($attributes))
        {
            //only delete entries for given attributes
            foreach($attributes as $key=>$value)
                $this->addOperation('DELETE','/index/node/'.urlencode($index).'/'.$key.'/'.$nodeId);
        }
        else
        {
            //delete all entries for this property container
            $this->addOperation('DELETE','/index/node/'.urlencode($index).'/'.$nodeId);
        }
    }
    
    /**
     * Removes a property container from an index. If attributes are supplied only entries for given attribute keys will be deleted
     * If no attributes are given, all entries for the given property container will be deleted.
     * @param integer $relationshipId The id of the node to be removed from the index
     * @param string $index Name of the index this operation will be using
     * @param array $attributes Optional: An array of attributes to be deleted for this property container. If none are provided the node will be removed for all indexed properties
     */
    public function removeRelationshipFromIndex($relationshipId,$index,$attributes=array())
    {        
        if(!empty($attributes))
        {
            //only delete entries for given attributes
            foreach($attributes as $key=>$value)
                $this->addOperation('DELETE','/index/relationship/'.urlencode($index).'/'.$key.'/'.$relationshipId);
        }
        else
        {
            //delete all entries for this property container
            $this->addOperation('DELETE', '/index/relationship/'.urlencode($index).'/'.$relationshipId);
        }
    }

    /**
     * Execute the current transaction and receive the results according to the batch ids used per operation
     * @return mixed an array of results 
     */
    public function execute()
    {
        Yii::trace(get_class($this).'.execute()','ext.Neo4Yii.ENeo4jBatchTransaction');
        if($this->_operations) //if there are any operations, send post request, otherwise ignore it as it would return an error by Neo4j
        {
            $request=new EActiveResourceRequest;
            $request->setUri($this->getConnection()->site.'/batch');
            $request->setMethod('POST');
            $request->setData($this->_operations);

            return $this->getConnection()->execute($request);                
        }
    }
}

?>
