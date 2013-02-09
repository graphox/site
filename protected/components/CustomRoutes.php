<?php

namespace application\components;

/**
 * Class for custom (static) database routes.
 */
class CustomRoutes extends \CBaseUrlRule
{
	/**
	 * Creates a URL based on this rule.
	 * @param CUrlManager $manager the manager
	 * @param string $route the route
	 * @param array $params list of parameters (name=>value) associated with the route
	 * @param string $ampersand the token separating name-value pairs in the URL.
	 * @return mixed the constructed URL. False if this rule does not apply.
	 */
	public function createUrl($manager,$route,$params,$ampersand)
	{
		try
		{
		$model = \application\models\Route::model()->findByAttributes(array(
			'to' => $route
		));
		
		if($model !== null)
			return $model->to;
		
		return false;
				}
		catch(\CException $e)
		{
			var_dump($e);
		}
	}
	/**
	 * Parses a URL based on this rule.
	 * @param CUrlManager $manager the URL manager
	 * @param CHttpRequest $request the request object
	 * @param string $pathInfo path info part of the URL (URL suffix is already removed based on {@link CUrlManager::urlSuffix})
	 * @param string $rawPathInfo path info that contains the potential URL suffix
	 * @return mixed the route that consists of the controller ID and action ID. False if this rule does not apply.
	 */
	public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
	{
		try
		{
			$model = \application\models\Route::model()->findByAttributes(array(
				'to' => addslashes($pathInfo)
			));

			if($model !== null)
				return $model->from;

			return false;
		}
		catch(\CException $e)
		{
			var_dump($e);
		}
	}
}