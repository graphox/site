<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'acl-privilege-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/acl/privileges/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/acl/privileges/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>






<fieldset>
		<?=$form->labelEx($form_model,'read'); ?>		<?=$form->textField($form_model,'read')?>
		<?=$form->error($form_model,'read')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'write'); ?>		<?=$form->textField($form_model,'write')?>
		<?=$form->error($form_model,'write')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'update'); ?>		<?=$form->textField($form_model,'update')?>
		<?=$form->error($form_model,'update')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'delete'); ?>		<?=$form->textField($form_model,'delete')?>
		<?=$form->error($form_model,'delete')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'order_by'); ?>		<?=$form->textField($form_model,'order_by')?>
		<?=$form->error($form_model,'order_by')?></fieldset>


<label for="AclObject">Belonging AclObject</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'object',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="AclGroup">Belonging AclGroup</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'group',
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
