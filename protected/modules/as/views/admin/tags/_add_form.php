<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'tags-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/tags/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/tags/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<?=$form->labelEx($form_model,'tag'); ?>		<?=$form->textField($form_model,'tag')?>
		<?=$form->error($form_model,'tag')?></fieldset>



<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
