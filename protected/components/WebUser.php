<?php

class WebUser extends CWebUser
{

	public function getEntity($refresh = false)
	{
		static $entity;
		
		if(!isset($entity) || $refresh === true)
			$entity = Entity::model()->internal()->findByPk($this->getModel($refresh)->entity_id);
	
		if($entity === null)
			throw new CException('User seems to have no entity.');
		
		return $entity;
	}
	
	public function getModel($refresh = false)
	{
		static $model;
		
		if(!isset($model) || $refresh === true)
			$model = User::model()->findByPk(Yii::app()->user->id);
		
		if($model === null)
			throw new CException('Could not find user! ('.print_r(Yii::app()->user->id, true).')');
		
		return $model;
	}
}
