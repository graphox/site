<?php
class Repo extends CBaseUrlRule
{
	public $connectionID = 'db';
 
	public function createUrl($manager,$route,$params,$ampersand)
	{
		/*if ($route==='car/index')
		{
			if (isset($params['manufacturer'], $params['model']))
				return $params['manufacturer'] . '/' . $params['model'];
			else if (isset($params['manufacturer']))
				return $params['manufacturer'];
		}//*/
		return false;  // this rule does not apply
	}
 
	public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
	{
		return false;
	}
}
