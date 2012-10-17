<?php
$this->breadcrumbs=array(
	'Admin'=>array('/admin/index'),
	'Pages'=>array('index'),
	$model->name=>array('view','name'=>$model->routeName	),
	'Update',
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List User', 'url'=>array('index')),
	array('icon' => 'trash', 'label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','name'=>$model->routeName),'confirm'=>'Are you sure you want to delete this item?')),
	array('icon' => 'search', 'label'=>'View User', 'url'=>array('view', 'name'=>$model->routeName)),
);

?>

<h1>Update page <?=CHtml::encode($model->name) ?></h1>

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>true,
	)); ?>
	
		<?=$form->errorSummary($model)?>
	
		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<?=$form->textFieldRow($model, 'name', array('class'=>'span3')); ?>
		<?=$form->textAreaRow($model, 'contentSource', array('class'=>'span3')); ?>
			
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.BootButton', array('type' => 'primary', 'buttonType'=>'submit', 'icon' => 'ok', 'label'=>'Update page')); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>