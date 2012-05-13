<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'clan-tag-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/clans/tags/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/clans/tags/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<?=$form->labelEx($form_model,'tag'); ?>		<?=$form->textField($form_model,'tag')?>
		<?=$form->error($form_model,'tag')?></fieldset>




<fieldset>
		<?=$form->labelEx($form_model,'status'); ?>		<?=$form->textField($form_model,'status')?>
		<?=$form->error($form_model,'status')?></fieldset>


<label for="Clans">Belonging Clans</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'clan',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
