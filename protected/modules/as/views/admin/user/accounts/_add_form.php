<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'user-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/user/accounts/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/user/accounts/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<?=$form->labelEx($form_model,'username'); ?>		<?=$form->textField($form_model,'username')?>
		<?=$form->error($form_model,'username')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'ingame_password'); ?>		<?=$form->textField($form_model,'ingame_password')?>
		<?=$form->error($form_model,'ingame_password')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'email'); ?>		<?=$form->textField($form_model,'email')?>
		<?=$form->error($form_model,'email')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'hashing_method'); ?>		<?=$form->textField($form_model,'hashing_method')?>
		<?=$form->error($form_model,'hashing_method')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'web_password'); ?>		<?=$form->textField($form_model,'web_password')?>
		<?=$form->error($form_model,'web_password')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'salt'); ?>		<?=$form->textField($form_model,'salt')?>
		<?=$form->error($form_model,'salt')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'status'); ?>		<?=$form->textField($form_model,'status')?>
		<?=$form->error($form_model,'status')?></fieldset>



<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
