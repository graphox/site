<?php
class ServersPorlet extends DPortlet
{

	protected function renderContent()
	{
		$servers = Servers::model()->findAllByAttributes(array('online' => '1'));
		
		if(count($servers) < 1)
			echo 'There are <strong>0</strong> servers online.';
		else
		{
			echo '<ul>';
			
			foreach($servers as $server)
				echo '<li><strong>'.$server->name.'</strong> '.$server->map.'('.$server->mode.')</li>';
			
			echo '</ul>';
		}				
	}
	
	protected function getTitle()
	{
		return 'Servers';
	}
	
	protected function getClassName()
	{
		return __CLASS__;
	}
}
