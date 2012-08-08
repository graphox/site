<?php
$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('icon' => 'plus', 'label'=>'Create User', 'url'=>array('create')),
	array('icon' => 'briefcase', 'label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Users</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
