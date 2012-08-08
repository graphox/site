<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List User', 'url'=>array('index')),
	array('icon' => 'plus', 'label'=>'Create User', 'url'=>array('create')),
	array('icon' => 'pencil', 'label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('icon' => 'trash', 'label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('icon' => 'briefcase', 'label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'entity_id',
		'username',
		'password',
		'status',
		'registered_date',
		'activated_date',
		'last_login',
	),
)); ?>
