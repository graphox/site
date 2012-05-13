<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'pm-message-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/pm/messages/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/pm/messages/add'))
	
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
		<?=$form->labelEx($form_model,'receiver_deleted'); ?>		<?=$form->textField($form_model,'receiver_deleted')?>
		<?=$form->error($form_model,'receiver_deleted')?></fieldset>




<fieldset>
		<?=$form->labelEx($form_model,'title'); ?>		<?=$form->textField($form_model,'title')?>
		<?=$form->error($form_model,'title')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'content'); ?>		<?=$form->textArea($form_model,'content')?>
		<?=$form->error($form_model,'content')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'sended_date'); ?>		<?=$form->textArea($form_model,'sended_date')?>
		<?=$form->error($form_model,'sended_date')?></fieldset>


<label for="PmDirectory">Belonging PmDirectory</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'receiverDir',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="User">Belonging User</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'sender',
							'fields' => 'username',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="User">Belonging User</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'receiver',
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
