<?php
$this->breadcrumbs=array(
	'User'=>array('/user'),
	'Login',
);

$this->pagetitle = Yii::app()->name . ' - login';
?>
<div class="span5">
	<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
		'id'=>'login-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>
	
		<?=$form->errorSummary($model)?>
	
		<p class="note">Fields with <span class="required">*</span> are required.</p>
		<?=$form->textFieldRow($model, 'username', array('class'=>'span3')); ?>
		<?=$form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>
		<?=$form->checkboxRow($model, 'remember_me'); ?>	
	
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'icon' => 'ok', 'label'=>'Login')); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>
