<?php
class FriendsPorlet extends DPortlet
{

	protected function renderContent()
	{
		$db = Yii::app()->db;
		
		$sql = 'SELECT user.username, online_player.name, servers.name AS server_name, servers.ip, servers.map, servers.mode, online_player.end_time FROM user, friends, online_player, servers WHERE friends.status = 2 AND (user.id = friends.owner_id OR user.id = friends.friend_id ) AND online_player.user_id = user.id AND (ADDTIME(online_player.end_time, \'0:0:05\') > NOW()) AND servers.id = online_player.server_id AND (:user_id = friends.owner_id OR :user_id = friends.friend_id) AND NOT user.id = :user_id';
		
		$command = $db->createCommand($sql);
		
		$id = Yii::app()->user->id;
		
		$command->bindParam(':user_id', $id);
		
		$result = $command->query()->readAll();
		
		if(count($result) > 0)
		{
			echo CHtml::tag('div', array('id' => 'online-player-full', 'style' => 'display:none'));
				echo CHtml::tag('table', array('class' => 'sortable'));
					echo CHtml::tag('thead');
					foreach($result[0] as $col => $_)
					{
						echo CHtml::tag('td');
						echo CHtml::encode($col);
						echo CHtml::closeTag('td');
					}				
					echo CHtml::closeTag('thead');
					echo CHtml::tag('tbody');
						foreach($result as $row)
						{
							echo CHtml::tag('tr');
							foreach($row as $col)
							{
								echo CHtml::tag('td');
								echo CHtml::encode($col);
								echo CHtml::closeTag('td');
							}
							echo CHtml::closeTag('tr');
						}
					echo Chtml::closetag('tbody');
				echo CHtml::closeTag('table');
			echo CHtml::closeTag('div');
			
			echo CHtml::tag('div');
				foreach($result as $row)
				{
					echo CHtml::tag('span');
						echo CHtml::encode($row['name']);
					echo CHtml::closeTag('span');
					echo ',';
				}
			echo CHtml::closeTag('div');
			echo CHtml::tag('a', array('href' => 'javascript:void(0)', 'onclick' => '$("body").dialog("Online Players");'));
				echo 'More info TODO:fix';
			echo CHtml::closeTag('a');
		}
		else
			echo 'you have <strong>0</strong> online friends';
	}
	
	protected function getTitle()
	{
		return 'Online Friends';
	}
	
	protected function getClassName()
	{
		return __CLASS__;
	}
}
