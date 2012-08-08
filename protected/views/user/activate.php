<?php
$this->breadcrumbs=array(
	'User'=>array('/user'),
	'Activate',
);
$this->pagetitle = Yii::app()->name . ' - activate';
?>

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
		'id'=>'activate-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>
	
		<?=$form->errorSummary($model)?>
	
		<p class="note">Fields with <span class="required">*</span> are required.</p>
		
		<?=$form->textFieldRow($model, 'activation_key', array('class'=>'span3')); ?>

		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'icon' => 'ok', 'label'=>'Activate')); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>
