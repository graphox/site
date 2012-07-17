<?php if(Yii::app()->user->hasFlash('attachment.success')): ?>
	<div class="flash-success">
		<?=Yii::app()->user->getFlash('attachment.success')?>
	</div>
<?php elseif(!Yii::app()->user->isGuest && $can->addAttachment): ?>

	<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'content-form',
		'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); ?>

		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'uploadedFile'); ?>
			<?php echo $form->fileField($model,'uploadedFile'); ?>
			<?php echo $form->error($model,'uploadedFile'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'content'); ?>
			<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'type_id'); ?>
			<?php echo $form->dropDownList($model,'type_id', $model->typeOptions); ?>
			<?php echo $form->error($model,'type_id'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'language_id'); ?>
			<?php echo $form->dropDownList($model,'language_id', $model->languageOptions); ?>
			<?php echo $form->error($model,'language_id'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'can_comment'); ?>
			<?php echo $form->checkBox($model,'can_comment', array('value'=>'1', 'uncheckValue'=>'0')); ?>
			<?php echo $form->error($model,'can_comment'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'markup_id'); ?>
			<?php echo $form->dropDownlist($model,'markup_id', $model->markupOptions); ?>
			<?php echo $form->error($model,'markup_id'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'published'); ?>
			<?php echo $form->checkBox($model,'published', array('value'=>'1', 'uncheckValue'=>'0')); ?>
			<?php echo $form->error($model,'published'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'widgets_enabled'); ?>
			<?php echo $form->checkBox($model,'widgets_enabled', array('value'=>'1', 'uncheckValue'=>'0')); ?>
			<?php echo $form->error($model,'widgets_enabled'); ?>
		</div>
	
		<div class="row buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		</div>

	<?php $this->endWidget(); ?>

	</div><!-- form -->
<?php elseif(!Yii::app()->user->isGuest): ?>
	<div class="flash-notice">
		You are not allowed to upload an attachment here.
	</div>
<?php else:?>
	<div class="flash-notice">
		Please <?=CHtml::link('login', array('//as/auth/auth'))?> or <?=CHtml::link('register', array('//as/auth/register'))?> to log in.
	</div>
<?php endif; ?>
