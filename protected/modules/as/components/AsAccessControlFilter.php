<?php

/**
	@class AsAccessControlFilter
	filter access
*/

class AsAccessControlFilter extends CFilter
{
	/**
		@var defaults an array containing default values for actions
	*/
	public $defaults = array();
	
	/**
		@var default_value the default value to use when the action is not found in the defaults array
	*/
	public $default_value = false;

    protected function checkauth($filterChain)
    {
		$access = Yii::app()->accessControl->getAccess('controller.'.$filterChain->controller->id, array(
			$filterChain->controller->action->id => isset($this->defaults[$filterChain->controller->action->id]) ? $this->defaults[$filterChain->controller->action->id] : $this->default_value
		));
		
		$access = $access->{$filterChain->controller->action->id};
		
        return $access;
    }
    
	protected function preFilter($filterChain)
	{
		if(!$this->checkAuth($filterChain))
			$filterChain->controller->denyAccess();
		else
			return true;
	}
}
