<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'clan-ranks-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/clans/ranks/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/clans/ranks/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<?=$form->labelEx($form_model,'name'); ?>		<?=$form->textField($form_model,'name')?>
		<?=$form->error($form_model,'name')?></fieldset>






<label for="AclGroup">Belonging AclGroup</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'aclGroup',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="Clans">Belonging Clans</label>
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
