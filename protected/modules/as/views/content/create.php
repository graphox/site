<?php
$this->breadcrumbs=array(
	'Contents'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Content', 'url'=>array('index')),
	array('label'=>'Manage Content', 'url'=>array('admin')),
);
?>

<h1>Create Content</h1>

<?php if(!isset($model->type_id)): ?>
	<h2>Please select a type</h2>
	
	<ul>
		<?php foreach(ContentType::model()->findAll() as $type): ?>
			<li>
				<?=CHtml::link(
					CHtml::encode($type->name),
					array('//as/content/create', 'type' => $type->name)
				)?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
	<?=$this->renderPartial('_form', array('model'=>$model, 'isUpdate' => false)); ?>
<?php endif; ?>
