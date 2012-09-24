<?php
$this->breadcrumbs=array(
	'Admin'=>array('/admin/index'),
	'Users'=>array('index'),
	$model->username=>array('view','name'=>$model->username	),
	'Update',
);

$this->menu=array(
	array('icon' => 'list', 'label'=>'List User', 'url'=>array('index')),
	array('icon' => 'trash', 'label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','name'=>$model->username),'confirm'=>'Are you sure you want to delete this item?')),
	array('icon' => 'search', 'label'=>'View User', 'url'=>array('view', 'name'=>$model->username)),
);

?>

<h1>Update user <?=CHtml::encode($model->displayName) ?></h1>

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>true,
	)); ?>
	
		<?=$form->errorSummary($model)?>
	
		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<?=$form->textFieldRow($model, 'username', array('class'=>'span3')); ?>
		<?=$form->textFieldRow($model, 'email', array('class'=>'span3')); ?>
		<?=$form->checkBoxRow($model, 'isAdmin'); ?>
	
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.BootButton', array('type' => 'primary', 'buttonType'=>'submit', 'icon' => 'ok', 'label'=>'Update user')); ?>
		</div>
	<?php $this->endWidget(); ?>
		
					<?php $this->widget('bootstrap.widgets.BootButtonGroup', array(
				'type'=>'secondary',
				'buttons'=>array(
					array('label'=>'Activate (admin).', 'url'=>array('/admin/user/activate', 'name' => $model->username)),
					array('items' => array(
						array('label'=>'Force email activation.', 'url'=>array('/admin/user/emailActivate', 'name' => $model->username), 'type' => 'secondary'),
						array('label'=>'Resend email (TODO)', 'url'=>'#'),	
					)),
					array('label'=>'Ban', 'url'=>array('/admin/user/ban', 'name' => $model->username)),
					array('label'=>'Update', 'url'=>array('/admin/user/update', 'name' => $model->username))
				),
			)); ?>
</div>
