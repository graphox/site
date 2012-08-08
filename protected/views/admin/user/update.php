<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List User', 'url'=>array('index')),
	array('icon' => 'plus', 'label'=>'Create User', 'url'=>array('create')),
	array('icon' => 'search', 'label'=>'View User', 'url'=>array('update', 'id'=>$model->id)),
	array('icon' => 'trash', 'label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('icon' => 'briefcase', 'label'=>'Manage User', 'url'=>array('admin')),
);

?>

<h1>Update User <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', get_defined_vars()); ?>
