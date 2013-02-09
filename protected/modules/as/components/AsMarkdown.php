<?php

class AsMarkdown extends CComponent
{
	public function init()
	{
	
	}
	
	/**
	 * @return the supported markdown types as activerecord classes
	 */
	
	public function getSupportedTypes()
	{
		return Markup::model()->findAll();
	}
	
	/**
	 * wrapper around the activeRecord
	 * @attribute name the name of the markup type
	 * @return the markup class
	 */
	public function findByName($name)
	{
		return Markup::model()->findByAttributes(array('name' => $name));
	}
	
	/**
	 * Adds a markdown type
	 * @return boolean success
	 */	
	public function addSupportedType($name, $class)
	{
		$aclObject = Yii::app()->accessControl->addObject('markup.'.$name);
		
		$model = new Markup;
		$model->name = $name;
		$model->{"class"} = $class;
		$model->acl_object_id = $aclObject->id;
		
		return $model->save(false);
	}
	
	/**
	 * deletes a type
	 * @attribute name the name of the markup
	 * @return boolean successs
	 */	
	public function deleteSupportedType($name)
	{
		return $this->findByName($name)->delete();
	}
	
	/**
	 * @return the types the user is allowed to perform as id => name pairs
	 */
	public function getAllowedTypes()
	{
		return CHtml::listData(
			Markup::model()->findAllWithAccess(array(
				'use' => false
			)),
			'id', 'name'
		);
	}
	
	/**
	 * Performs markup on the content
	 * @attribute typeName the name of the markup
	 * @attribute content the content to markup
	 * @return the marked up html
	 */	
	public function performMarkup($typeName, $content)
	{
		return $this->findByName($typename)->render($content);
	}
}
