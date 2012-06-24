<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'message-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('//as/user/newMessage'),
	'clientOptions' => array(
		'validationUrl' => array('//as/user/newMessage'),
	),
	'htmlOptions' => array(
		'class' => 'admin-form',
	)
)); ?>
	<h3>new message</h3>
	<p>
		<fieldset>
			<?=$form->labelEx($model,'title')?>
			<?=$form->textField($model,'title')?>
			<?=$form->error($model,'title')?>
		</fieldset>

		<fieldset>
			<?=$form->labelEx($model,'receiver')?>
			<?=$form->textField($model,'receiver')?>
			<?=$form->error($model,'receiver')?>
		</fieldset>

		<fieldset>
			<?=$form->labelEx($model,'content'); ?>
			<?=$form->textArea($model,'content')?>
			<?=$form->error($model,'content')?>
		</fieldset>

		<div style="margin-left:237px;">
			<?= CHtml::submitButton('save', array('id' => 'submit')); ?>
		</div>
	</p>
<?php $this->endWidget(); ?>
