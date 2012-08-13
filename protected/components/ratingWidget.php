<?php

class ratingWidget extends CWidget
{
	public $parent;
	
	public function run()
	{
		$model = new RatingForm;
		$model->parentId = $this->parent->id;
		$this->controller->renderPartial('//rating/vote', array('parent' => $this->parent, 'model' => $model));
	}	
}