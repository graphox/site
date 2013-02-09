<?php
$this->breadcrumbs=array(
	'Admin'=>array('/admin/index'),
	'Pages'=>array('index'),
	$model->routeName,
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List User', 'url'=>array('index')),
	array('icon' => 'create', 'label'=>'CreatePage', 'url'=>array('create')),
	array('icon' => 'pencil', 'label'=>'Update User', 'url'=>array('update', 'name'=>$model->routeName)),
	array('icon' => 'trash', 'label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','name'=>$model->routeName),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View page <?=CHtml::encode($model->name); ?></h1>
<div class="well">
	<?=$model->content?>
</div>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id:number',
		'name',
		'contentSource',
		'routeName',
	),
)); ?>

<h2>Admin actions</h2>
<nav>
	   <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
			'type'=>'primary',
			'buttons'=>array(
				array('label'=>'Update', 'url'=>array('/admin/page/update', 'name' => $model->routeName)),
				array('label'=>'View in action', 'url'=>array('/'.$model->routeName))
			),
		)); ?>
</nav>
