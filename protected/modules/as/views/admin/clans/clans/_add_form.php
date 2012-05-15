<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'clans-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/clans/clans/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/clans/clans/add'))
	
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
		<?=$form->labelEx($form_model,'status'); ?>		<?=$form->textField($form_model,'status')?>
		<?=$form->error($form_model,'status')?></fieldset>






<label for="AclGroup">Belonging AclGroup</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'aclGroup',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="Pages">Belonging Pages</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'page',
							'fields' => 'module',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="Forum">Belonging Forum</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'forum',
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
