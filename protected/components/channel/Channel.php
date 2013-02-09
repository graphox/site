<?php

/**
 * channel of a stream
 */
class Channel extends Neo4JNode
{
	//const doesn't allow array
	public static $TYPES = array(
		'private'	=> 'PrivateChannel',
		'friends'	=> 'FriendsChannel',
		'public'	=> 'PublicChannel'
	);
	
	private function getChannelType($user)
	{
		if(!Yii::app()->user->isGuest)
		{
			if(Yii::app()->user->id == $user->id)
				return self::$TYPES['private'];
			elseif($user->isFriend(Yii::app()->user->id))
				return self::$TYPES['friends'];
		}
		
		return self::$TYPES['public'];
	}
	
	public function findActions($user, PaginationOptions &$options)
	{
		return $user->getChannel(
				$this->getChannelType($user)
		)->findActions($options);
		
	}
}
