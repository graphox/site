<?php
$this->breadcrumbs=array(
	'Page Comments'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List PageComments', 'url'=>array('index')),
	array('label'=>'Create PageComments', 'url'=>array('create')),
	array('label'=>'Update PageComments', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PageComments', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PageComments', 'url'=>array('admin')),
);
?>

<h1>View PageComments #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'page_id',
		'title',
		'content',
		'posted_date',
	),
)); ?>
