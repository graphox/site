<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
	'type'=>'horizontal',
)); ?>
	
	<?=$form->errorSummary($model)?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php $model->password = '';?>
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<?php if(!$model->isNewRecord): ?>
		<h3>User details</h3>
		<p>
			<?php $this->widget('bootstrap.widgets.BootDetailView', array(
				'data'=>$model,
				'attributes'=>array(
					array('name'=>'registered_date', 'label'=>'registered date'),
					array('name'=>'activated_date', 'label'=>'activated_date'),
					array('name'=>'last_login', 'label'=>'last_login'),
				),
			)); ?>
		</p>
	<?php else: ?>
		<div class="row">
			<?php echo $form->labelEx($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',	$model->statusOptions); ?>
			<?php echo $form->error($model,'status'); ?>	
		</div>
	<?php endif; ?>
	
	<div class="form-actions">
		<?= $form->labelEx($model, 'access')?>
		<?=$form->dropDownList($model, 'access', $model->_entity->accessOptions)?>
		<?=$form->error($model, 'access'); ?>

		<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>$model->isNewRecord ? 'Create' : 'Save')); ?>
	</div>
<?php $this->endWidget(); ?>
