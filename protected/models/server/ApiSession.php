<?php

/**
 * Entity for server connections
 *
 * @author killme
 * 
 * @property int $id
 * @property string $key
 * @property string $host
 * @property int $lastAction
 */
class ApiSession extends ENeo4jNode
{
	/**
	 * @return ApiSession returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
	
	/**
	 * @return array, the properties of the node.
	 * @todo add server settings
	 */
    public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'key'				=>	array('type'=>'string'),
			'host'				=>	array('type'=>'string'),
			'lastAction'		=>	array('type' =>'integer'),
        ));
    }
	
	public function getTimeoutTime()
	{
		return time() - (60 * 60 * 3);
	}
	
	public function checkActionTime()
	{
		if($this->lastAction > $this->getTimeoutTime())
			return true;
		
		$this->cleanUp();		
		return false;
	}
	
	public function cleanUp()
	{
		$clean = self::model()->findAllByQuery('g.V.filter{it.lastAction < '.(int)$this->getTimeoutTime());
		
		foreach($clean as $model)
			$model->remove();
	}

	const SESSIONHASHSIZE = 15;
	const SESSIONKEY = 16546354324;
	
	public function encodeSessionID($sessionID)
	{
		static $hashSize = 15;
		static $privateKey = 16546354324; 

		return base_convert(
			$sessionID * self::SESSIONHASHSIZE
				+ (
					$sessionId * self::SESSIONKEY
				) % self::SESSIONHASHSIZE,
			10,
			36
		);
	}
	
	function decodeSessionID($sessionId)
	{
		$number = (int) base_convert($sessionId, 36, 10);
		$id = ($number - ($number % self::SESSIONHASHSIZE)) / self::SESSIONHASHSIZE;
		if(($number % self::SESSIONHASHSIZE) != ($id * self::SESSIONHASHSIZE) % self::SESSIONHASHSIZE)
			throw new CException('Invalid session id.');
		return $id;
	}

	public function findBySessionId($sessionID)
	{
		return self::model()->findById(
			$this->decodeSessionID($sessionID)
		);
	}
	
}
