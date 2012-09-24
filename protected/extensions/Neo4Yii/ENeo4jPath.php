<?php
class ENeo4jPath extends CComponent
{   
    private $_propertyContainers=array();
    private $_relationships;
    private $_nodes;
    
    /**
     * Return all property containers of this path
     * @return array All property containers this path holds
     */
    public function getPropertyContainers()
    {
        return $this->_propertyContainers;
    }
    
    /**
     * Return all relationships of this path
     * @return array All relationships this path holds
     */
    public function getRelationships()
    {
        if(isset($this->_relationships))
                return $this->_relationships;
        
        $relationships=array();
        foreach($this->_propertyContainers as $propertyContainerClass)
        {
            foreach($propertyContainerClass as $instance)
            {
                if($instance instanceof ENeo4jRelationship)
                    $relationships[]=$propertyContainer;
            }
        }
        
        return $this->_relationships=$relationships;
                
    }
    
    /**
     * Return all nodess of this path
     * @return array All nodes this path holds
     */
    public function getNodes()
    {
        if(isset($this->_nodes))
                return $this->_nodes;
        
        $nodes=array();
        foreach($this->_propertyContainers as $propertyContainerClass)
        {
            foreach($propertyContainerClass as $instance)
            {
                if($instance instanceof ENeo4jNode)
                    $nodes[]=$propertyContainer;
            }
        }
        
        return $this->_nodes=$nodes;
                
    }
    
    /**
     * Return all property containers of given classname
     * @param $className The class of property containers that have to be returned
     * @return array All property containers of the given class this path holds
     */
    public function getNamedPropertyContainers($className)
    {
        if(isset($this->_propertyContainers[$className]))
                return $this->_propertyContainers[$className];
        else
            return array();
    }
    
    /**
     * Adds a property container to this path. Used internally by populatePath
     * @param ENeo4jPropertyContainer The property container to be added
     * @param ENeo4jPropertyContainer $propertyContainer 
     */
    protected function addPropertyContainer(ENeo4jPropertyContainer $propertyContainer)
    {
        $this->_propertyContainers[get_class($propertyContainer)][]=$propertyContainer;
    }
    
    /**
     * Populates multiple paths with property containers.
     * @param array $data An array of paths
     * @return array An array of populated Path objects 
     */
    public static function populatePaths($data)
    {
        $paths=array();
        if(is_array($data))
        {
            foreach($data as $path)
                $paths[]=self::populatePath($path);
        }
        return $paths;
    }
    
    /**
     * Populates a single path with property containers
     * @param array $data data of a single path response
     * @return self The path object filled with property containers.
     */
    public static function populatePath($data)
    {
        $path=new self;
        if(is_array($data))
        {
            foreach($data as $propertyContainer)
            {
                //is a node
                if(strpos($propertyContainer['self'],'/node/')>0)
                    $path->addPropertyContainer(ENeo4jNode::model()->populateRecord($propertyContainer));
                //is a relationship
                if(strpos($propertyContainer['self'],'/relationship/')>0)
                    $path->addPropertyContainer(ENeo4jRelationship::model()->populateRecord($propertyContainer));
            }
        }
        return $path;
    } 
        
}
?>
