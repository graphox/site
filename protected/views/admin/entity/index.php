<?php
$this->breadcrumbs=array(
	'Entities',
);

$this->menu=array(
	array('label'=>'Create Entity','url'=>array('create')),
	array('label'=>'Manage Entity','url'=>array('admin')),
);
?>

<h1>Entities</h1>

<?php $this->widget('bootstrap.widgets.BootListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
