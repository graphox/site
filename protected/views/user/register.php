<?php
$this->breadcrumbs=array(
	'User'=>array('/user'),
	'Register',
);
$this->pagetitle = Yii::app()->name . ' - register';
?>

<h1>Account registration</h1>
	
<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
		'id'=>'register-form',
		'enableAjaxValidation'=>true,
		'type'=>'horizontal',
	)); ?>
	
		<?=$form->errorSummary($model)?>
	
		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<?=$form->textFieldRow($model, 'username', array('class'=>'span3')); ?>
		<?php $model->password = ''; ?>
		<?=$form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>
		<?php $model->retypePassword = ''; ?>
		<?=$form->passwordFieldRow($model, 'retypePassword', array('class'=>'span3')); ?>
		
		<?=$form->textFieldRow($model, 'email', array('class'=>'span3')); ?>
	
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'icon' => 'ok', 'label'=>'Register')); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>

