<?php
class AsAccessControlFilter extends CFilter
{
	public function init()
	{
	
	}
	
    protected function checkauth($filterChain)
    {
		if(method_exists($filterChain->controller, 'acRules'))
		{
			$app = Yii::app();
			$obj_name = 'site';
			$required_access = array( 'read' );
			
			foreach($filterChain->controller->acRules() as $name => $rule) switch($name)
			{
				case 'acl_object_name':
					$obj_name = $rule;
					break;
				
				case 'actions':
					foreach($rule as $action => $access)
					{
						if($action == $filterChain->action->id)
						{
							$required_access = $access;
							break;
						}
					}
					break;
			}
			
			try
			{
				$access = AccessControl::GetAccess($obj_name);
				
				if($access === false)
					throw new exception('Could not auth !');
				
				if(in_array('read', $required_access) && !$access->read)
					return false;
				elseif(in_array('update', $required_access) && !$access->update)
					return false;
				elseif(in_array('write', $required_access) && !$access->write)
					return false;
				elseif(in_array('delete', $required_access) && !$access->delete)
					return false;
				
			}
			catch(exception $e)
			{
				Yii::log('Could not find user\'s Access to object '.$obj_name.' for user_id '.$app->user->id, 'error', 'auth');
				
				if(count($required_access) === 0)
					return true;
				else
					return false;
			}
		}
		else
			throw new exception('Could not find accessrules!');
        // logic being applied before the action is executed
        return true; // false if the action should not be executed
    }
    
	protected function preFilter($filterChain)
	{
		if(!$this->checkAuth($filterChain))
		{
/*			switch(Yii::app()->controller->module->onAuthFail[0])
			{
				case 'announce':*/
					$filterChain->controller->render('as.views.acl.deny');
					Yii::app()->end();
					return false;
					#break;
			}
		else
			return true;
	}
}
