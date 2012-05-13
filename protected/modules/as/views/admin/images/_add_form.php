<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'images-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/images/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/images/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<?=$form->labelEx($form_model,'name'); ?>		<?=$form->textField($form_model,'name')?>
		<?=$form->error($form_model,'name')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'description'); ?>		<?=$form->textArea($form_model,'description')?>
		<?=$form->error($form_model,'description')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'added_date'); ?>		<?=$form->textArea($form_model,'added_date')?>
		<?=$form->error($form_model,'added_date')?></fieldset>




<label for="User">Belonging User</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'ownedBy',
							'fields' => 'username',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
