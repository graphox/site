<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'menu-item-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/menu/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/menu/add'))
	
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
		<?=$form->labelEx($form_model,'url'); ?>		<?=$form->textArea($form_model,'url')?>
		<?=$form->error($form_model,'url')?></fieldset>




<label for="AclObject">Belonging AclObject</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'aclObject',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="MenuItem">Belonging MenuItem</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'parent',
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
