<?php
$this->breadcrumbs=array(
	'Page Comments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PageComments', 'url'=>array('index')),
	array('label'=>'Manage PageComments', 'url'=>array('admin')),
);
?>

<h1>Create PageComments</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>