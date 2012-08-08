<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List Users', 'url'=>array('index')),
	array('icon' => 'briefcase', 'label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>Create User</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
