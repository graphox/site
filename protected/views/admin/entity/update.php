<?php
$this->breadcrumbs=array(
	'Entities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Entity','url'=>array('index')),
	array('label'=>'Create Entity','url'=>array('create')),
	array('label'=>'View Entity','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Entity','url'=>array('admin')),
);
?>

<h1>Update Entity <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>