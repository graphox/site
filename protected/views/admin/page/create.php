<?php
$this->breadcrumbs=array(
	'Admin'=>array('/admin/index'),
	'Users'=>array('index'),
	$model->name=>array('view','name'=>$model->routeName),
	'Update',
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List Pages', 'url'=>array('index')),
	array('icon' => 'create', 'label'=>'CreatePage', 'url'=>array('create')),
	array('icon' => 'trash', 'label'=>'Delete Page', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','name'=>$model->routeName),'confirm'=>'Are you sure you want to delete this item?')),
	array('icon' => 'search', 'label'=>'View Page', 'url'=>array('view', 'name'=>$model->routeName)),
);

?>

<h1>Create new user.</h1>

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>true,
	)); ?>
	
		<?=$form->errorSummary($model)?>
	
		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<?=$form->textFieldRow($model, 'name', array('class'=>'span5')); ?>
		<?php echo $form->markdownEditorRow($model,'contentSource'); ?>
	
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array('type' => 'primary', 'buttonType'=>'submit', 'icon' => 'ok', 'label'=>'Create Page')); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>
