<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'pm-directory-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/pm/directories/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/pm/directories/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>






<fieldset>
		<?=$form->labelEx($form_model,'name'); ?>		<?=$form->textField($form_model,'name')?>
		<?=$form->error($form_model,'name')?></fieldset>


<label for="User">Belonging User</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'user',
							'fields' => 'username',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="PmDirectory">Belonging PmDirectory</label>
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
