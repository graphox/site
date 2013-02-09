<?php
$this->breadcrumbs=array(
	'Contents'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Content', 'url'=>array('index')),
	array('label'=>'Create Content', 'url'=>array('create')),
	array('label'=>'View Content', 'url'=>array('view', 'id'=>$model->id, 'name' => $model->name)),
	array('label'=>'Manage Content', 'url'=>array('admin')),
);

echo $this->renderPartial('_form', array('model'=>$model, 'isUpdate' => true))
