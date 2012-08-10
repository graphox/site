<?php

/**
 * CommentFormWidget provides a widget that gives users the abillity to comment on a parental entity by validating by ajax and posting it by ajax. clients with no support for javascript will make a regular request to the specified action.
 */
class commentFormWidget extends CWidget
{
	/**
	 * @var $parentEntity BaseEntity the parent entity.
	 */
	public $parentEntity;
	
	public function run()
	{
		$model = new CommentEntity;
		$model->parent_id = $this->parentEntity->id;
		$this->controller->renderPartial('//comment/add', array('model'=> $model));
	}
}