<?php
$this->breadcrumbs=array(
	'Page Comments'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PageComments', 'url'=>array('index')),
	array('label'=>'Create PageComments', 'url'=>array('create')),
	array('label'=>'View PageComments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PageComments', 'url'=>array('admin')),
);
?>

<h1>Update PageComments <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>