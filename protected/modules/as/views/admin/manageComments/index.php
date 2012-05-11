<?php
$this->breadcrumbs=array(
	'Page Comments',
);

$this->menu=array(
	array('label'=>'Create PageComments', 'url'=>array('create')),
	array('label'=>'Manage PageComments', 'url'=>array('admin')),
);
?>

<h1>Page Comments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
